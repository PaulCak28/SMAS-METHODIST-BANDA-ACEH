<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\ExamViolation;
use App\Models\Announcement;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $dbStats = DB::connection('mysql_admin')->select('CALL sp_dashboard_stats()')[0];

        $stats = [
            'Total Siswa' => $dbStats->total_students,
            'Total Guru' => $dbStats->total_teachers,
            'Total Kelas' => $dbStats->total_classes,
            'Mata Pelajaran' => $dbStats->total_subjects,
            'Total Ujian' => $dbStats->published_exams,
            'Pengumuman' => $dbStats->total_announcements,
        ];

        $activeSessions = \App\Models\ExamSession::on('mysql_admin')
            ->with('exam.classSubjectTeacher.schoolClass', 'exam.classSubjectTeacher.subject')
            ->where('status', 'berlangsung')
            ->latest('started_at')
            ->take(5)
            ->get();

        $sessionsRunning = $dbStats->active_sessions;

        $pendingEssays = DB::connection('mysql_admin')->table('v_essay_pending')->count();

        $studentsNoClass = DB::connection('mysql_admin')->table('students')->whereNull('class_id')->count();

        $recentAnnouncements = Announcement::on('mysql_admin')->latest()
            ->take(5)
            ->get();

        $violations = ExamViolation::on('mysql_admin')->with([
            'student.user',
            'session.exam'
        ])
            ->orderByDesc('occurred_at')
            ->take(5)
            ->get();

        $classes = SchoolClass::on('mysql_admin')->withCount('students')
            ->orderBy('name')
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'sessionsRunning',
            'activeSessions',
            'pendingEssays',
            'studentsNoClass',
            'recentAnnouncements',
            'violations',
            'classes'
        ));
    }
}