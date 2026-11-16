<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExamSession;
use App\Services\ExamGrader;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MonitoringController extends Controller
{
    public function index()
    {
        $activeSessions = DB::table('v_exam_statistics as vs')
            ->join('exams as e', 'e.exam_id', '=', 'vs.exam_id')
            ->select(
                'vs.exam_id as id',
                'vs.exam_type as name',
                'vs.exam_title',
                'vs.class_name',
                'vs.subject_name',
                'vs.total_sessions as participants_count',
                'vs.finished_sessions',
                'e.start_time',
                'e.end_time',
                'e.is_published'
            )
            ->selectRaw("
                CASE
                    WHEN fn_is_exam_open(vs.exam_id) = 1
                     AND NOT (
                         vs.total_sessions > 0
                         AND vs.finished_sessions >= (
                             SELECT COUNT(*) FROM cst_students cs
                             WHERE cs.cst_id = e.cst_id AND cs.status = 'aktif'
                         )
                     )
                    THEN 1 ELSE 0
                END AS is_open
            ")
            ->orderByDesc('is_open')
            ->orderByDesc('e.start_time')
            ->get()
            ->map(function ($row) {
                $row->exam = (object) ['title' => $row->exam_title];
                $row->cst = (object) [
                    'schoolClass' => (object) ['name' => $row->class_name],
                    'subject'     => (object) ['name' => $row->subject_name],
                ];

                return $row;
            });

        return view('admin.monitoring.index', compact('activeSessions'));
    }

    public function show($id, ExamGrader $grader)
    {
        // Tutup otomatis sesi ujian yang melewati batas waktu via Stored Procedure sp_find_expired_sessions
        $expiredIds = collect(DB::select('CALL sp_find_expired_sessions()'))
            ->where('exam_id', $id)
            ->pluck('exam_session_id');

        if ($expiredIds->isNotEmpty()) {
            ExamSession::with('exam')
                ->whereIn('exam_session_id', $expiredIds)
                ->get()
                ->each(fn ($s) => $grader->gradeSession($s));
        }

        // 1. Detail ujian dari View v_exam_statistics + join_code kelas
        $rawSession = DB::table('v_exam_statistics as vs')
            ->join('exams as e', 'e.exam_id', '=', 'vs.exam_id')
            ->join('class_subject_teacher as cst', 'cst.cst_id', '=', 'e.cst_id')
            ->select(
                'vs.*',
                'e.start_time',
                'e.end_time',
                'e.duration_minutes',
                'cst.join_code as token'
            )
            ->selectRaw("
                CASE
                    WHEN fn_is_exam_open(vs.exam_id) = 1
                     AND NOT (
                         vs.total_sessions > 0
                         AND vs.finished_sessions >= (
                             SELECT COUNT(*) FROM cst_students cs
                             WHERE cs.cst_id = e.cst_id AND cs.status = 'aktif'
                         )
                     )
                    THEN 1 ELSE 0
                END AS is_open
            ")
            ->where('vs.exam_id', $id)
            ->first();

        abort_if(! $rawSession, 404);

        $session = $rawSession;
        $session->exam = (object) ['title' => $rawSession->exam_title];
        $session->cst = (object) [
            'schoolClass' => (object) ['name' => $rawSession->class_name],
            'subject'     => (object) ['name' => $rawSession->subject_name],
        ];

        // 2. Ambil rekap pelanggaran dari View v_violation_summary per NIS siswa
        $violationMap = DB::table('v_violation_summary')
            ->where('exam_id', $id)
            ->select('nis', DB::raw('SUM(total) as total_violations'))
            ->groupBy('nis')
            ->pluck('total_violations', 'nis');

        // 3. Daftar peserta dari Stored Procedure sp_exam_participants
        $participants = collect(DB::select('CALL sp_exam_participants(?)', [$id]))
            ->map(function ($row) use ($violationMap) {
                // Ambil NIS secara aman (bisa dari kolom 'nis' ataupun 'students_id')
                $nis = $row->nis ?? $row->students_id ?? null;
                $row->nis = $nis;

                $row->start_time = !empty($row->started_at)
                    ? Carbon::parse($row->started_at)->format('H:i:s')
                    : '-';

                $row->finish_time = !empty($row->finished_at)
                    ? Carbon::parse($row->finished_at)->format('H:i:s')
                    : '-';

                // Gabungkan hasil fn_violation_count dengan v_violation_summary agar selalu akurat
                $row->violation_count = max(
                    (int) ($row->violation_count ?? 0),
                    (int) ($nis ? ($violationMap[$nis] ?? 0) : 0)
                );

                $row->student = (object) [
                    'nisn' => $nis ?? '-',
                    'user' => (object) ['name' => $row->student_name ?? $row->name ?? '-'],
                ];

                $row->score = $row->score ?? $row->objective_score ?? null;
                $row->session_status = $row->session_status ?? $row->status ?? 'belum_mulai';

                return $row;
            });

        // 4. Rincian jenis pelanggaran dari View v_violation_summary untuk ditampilkan di tabel bawah
        $violations = DB::table('v_violation_summary')
            ->where('exam_id', $id)
            ->orderByDesc('last_occurred_at')
            ->get();

        return view('admin.monitoring.show', compact('session', 'participants', 'violations'));
    }
}