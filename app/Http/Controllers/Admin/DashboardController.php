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
        $dbStats = DB::selectOne('CALL sp_dashboard_stats()');

        $stats = [
            'Total Siswa' => $dbStats->total_students,
            'Total Guru' => $dbStats->total_teachers,
            'Total Kelas' => $dbStats->total_classes,
            'Mata Pelajaran' => $dbStats->total_subjects,
            'Total Ujian' => $dbStats->total_exams,
            'Pengumuman' => Announcement::count(),
        ];

        $sessionsRunning = $dbStats->sessions_running;

        $pendingEssays = $dbStats->pending_essays;

        $studentsNoClass = $dbStats->students_no_class;

        $recentAnnouncements = Announcement::latest()
            ->take(5)
            ->get();

        $violations = ExamViolation::with([
            'student.user',
            'session.exam'
        ])
            ->orderByDesc('occurred_at')
            ->take(5)
            ->get();

        $classes = SchoolClass::withCount('students')
            ->orderBy('name')
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'sessionsRunning',
            'pendingEssays',
            'studentsNoClass',
            'recentAnnouncements',
            'violations',
            'classes'
        ));
    }
}