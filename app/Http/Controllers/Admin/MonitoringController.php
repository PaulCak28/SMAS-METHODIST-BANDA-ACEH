<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExamSession;
use App\Services\ExamGrader;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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

        // [BARU] Daftar kelas untuk kartu di index.blade.php (@forelse($classes as $class))
        $classes = DB::table('classes as c')
            ->select('c.*')
            ->selectRaw("(SELECT COUNT(*) FROM students s
                          WHERE s.class_id = c.class_id AND s.status = 'aktif') AS students_count")
            ->when(
                Schema::hasColumn('class_subject_teacher', 'class_id'),
                fn ($q) => $q->selectRaw("(SELECT COUNT(*) FROM exams e
                                           JOIN class_subject_teacher cst ON cst.cst_id = e.cst_id
                                           WHERE cst.class_id = c.class_id) AS exams_count")
            )
            ->orderBy('c.class_id')
            ->get();

        return view('admin.monitoring.index', compact('activeSessions', 'classes'));
    }

    // [BARU] Daftar ujian per kelas (route: admin.monitoring.class.exams)
    public function classExams($classId)
    {
        $class = DB::table('classes')->where('class_id', $classId)->first();
        abort_if(! $class, 404);

        $exams = DB::table('v_exam_statistics as vs')
            ->join('exams as e', 'e.exam_id', '=', 'vs.exam_id')
            ->join('class_subject_teacher as cst', 'cst.cst_id', '=', 'e.cst_id')
            ->where('cst.class_id', $classId)
            ->select(
                'vs.exam_id as id',
                'vs.exam_type',
                'vs.exam_title',
                'vs.subject_name',
                'vs.total_sessions',
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
            ->map(function ($row) use ($class) {
                // Bentuk sama dengan index(): agar kompatibel dengan blade yang memakai relasi
                $row->exam_id = $row->id;
                $row->name = $row->exam_type;
                $row->participants_count = $row->total_sessions;
                $row->class_name = $class->name ?? $class->class_name ?? null;
                $row->exam = (object) ['title' => $row->exam_title];
                $row->cst = (object) [
                    'schoolClass' => (object) ['name' => $row->class_name],
                    'subject'     => (object) ['name' => $row->subject_name],
                ];

                return $row;
            });

        // View memakai $schoolClass->name
        $schoolClass = $class;
        $schoolClass->name = $class->name ?? $class->class_name ?? ('Kelas ' . $classId);

        return view('admin.monitoring.class_exams', [
            'schoolClass' => $schoolClass,
            'class'       => $schoolClass,
            'exams'       => $exams,
            'sessions'    => $exams,
        ]);
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
                'e.pass_score', // [BARU] untuk penentuan lulus
                'cst.join_code as token',
                'cst.class_id'
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

        // [BARU] Cadangan NIS (nama => NIS), nilai (NIS => nilai), dan sesi (NIS => sesi).
        // Nama kolom dideteksi otomatis; bila tidak cocok, cadangan dilewati (tidak error).
        $col = fn (string $table, array $candidates) => collect($candidates)
            ->first(fn ($c) => Schema::hasColumn($table, $c));

        $nisByName = collect();
        $gradeMap = collect();
        $sessionMap = collect();

        try {
            $csNis = $col('cst_students', ['students_id', 'nis', 'student_id']);
            $userKey = $col('users', ['user_id', 'id']);
            if ($csNis && $userKey) {
                $nisByName = DB::table('exams as e')
                    ->join('cst_students as cs', function ($j) {
                        $j->on('cs.cst_id', '=', 'e.cst_id')->where('cs.status', 'aktif');
                    })
                    ->join('users as u', "u.{$userKey}", '=', "cs.{$csNis}")
                    ->where('e.exam_id', $id)
                    ->pluck("cs.{$csNis}", 'u.name');
            }
        } catch (\Throwable $e) {
            report($e);
        }

        try {
            $grNis = $col('grades', ['students_id', 'nis', 'student_id']);
            if ($grNis && Schema::hasColumn('grades', 'exam_id')) {
                $gradeMap = DB::table('grades')
                    ->where('exam_id', $id)
                    ->pluck('score', $grNis);
            }
        } catch (\Throwable $e) {
            report($e);
        }

        try {
            $esNis = $col('exam_sessions', ['students_id', 'nis', 'student_id']);
            if ($esNis) {
                $sessionMap = DB::table('exam_sessions')
                    ->where('exam_id', $id)
                    ->get(['exam_session_id', "{$esNis} as nis_key", 'status'])
                    ->keyBy('nis_key');
            }
        } catch (\Throwable $e) {
            report($e);
        }

        // 3. Daftar peserta dari Stored Procedure sp_exam_participants
        $participants = collect(DB::select('CALL sp_exam_participants(?)', [$id]))
            ->map(function ($row) use ($violationMap, $nisByName, $gradeMap, $sessionMap, $session) {
                $name = $row->student_name ?? $row->name ?? '-';

                // Ambil NIS (kolom 'nis' -> 'students_id' -> cari lewat nama)  [DIUBAH]
                $nis = $row->nis ?? $row->students_id ?? ($nisByName[$name] ?? null);
                $row->nis = $nis;
                $row->student_name = $name;

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
                    'user' => (object) ['name' => $name],
                ];

                $row->session_status = $row->session_status ?? $row->status
                    ?? ($nis ? ($sessionMap[$nis]->status ?? null) : null)
                    ?? 'belum_mulai';

                $row->exam_session_id = $row->exam_session_id
                    ?? ($nis ? ($sessionMap[$nis]->exam_session_id ?? null) : null);

                // Nilai: dari SP -> tabel grades -> hitung langsung bila sesi selesai  [DIUBAH]
                $score = $row->score ?? $row->objective_score ?? null;

                if ($score === null && $nis) {
                    $score = $gradeMap[$nis] ?? null;
                }

                if ($score === null && $row->session_status === 'selesai' && !empty($row->exam_session_id)) {
                    $score = DB::selectOne(
                        'SELECT fn_exam_objective_score(?) AS score',
                        [$row->exam_session_id]
                    )->score ?? null;
                }

                $row->score = $score;
                $row->passed = $score === null
                    ? null
                    : ((float) $score >= (float) ($session->pass_score ?? 0));

                return $row;
            });

        // 4. Rincian jenis pelanggaran dari View v_violation_summary untuk ditampilkan di tabel bawah
        $nameByNis = $participants->pluck('student_name', 'nis');

        $violations = DB::table('v_violation_summary')
            ->where('exam_id', $id)
            ->orderByDesc('last_occurred_at')
            ->get()
            ->map(function ($v) use ($nameByNis) { // [BARU] normalisasi field untuk blade
                $v->student_name = $v->student_name ?? ($nameByNis[$v->nis] ?? '-');
                $v->type = $v->violation_type ?? $v->type ?? $v->jenis ?? '-';
                $v->occurred_at = $v->last_occurred_at ?? $v->occurred_at ?? null;
                $v->description = $v->description
                    ?? 'Terdeteksi meninggalkan halaman ujian (pindah tab browser).';

                return $v;
            });

        return view('admin.monitoring.show', compact('session', 'participants', 'violations'));
    }
}