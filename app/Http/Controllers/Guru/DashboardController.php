<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $teacher = $request->user()->teacher;
        abort_unless($teacher, 403, 'Akun ini bukan akun guru.');

        $connection = DB::connection('mysql_guru');
        $overview = collect($connection->select('CALL sp_teacher_overview(?)', [$teacher->nip]));
        $cstIds = $overview->pluck('cst_id')->all();

        $gradeSummaries = collect();
        foreach ($overview as $class) {
            $gradeSummaries->put(
                $class->cst_id,
                collect($connection->select(
                    'CALL sp_teacher_student_grades(?, ?)',
                    [$teacher->nip, $class->cst_id]
                ))
            );
        }

        $gradebook = $connection->table('v_gradebook')
            ->whereIn('cst_id', $cstIds)
            ->orderBy('student_name')
            ->get();
        $assignmentStatuses = $connection->table('v_assignment_status')
            ->whereIn('cst_id', $cstIds)
            ->orderBy('due_date')
            ->get();
        $roster = $connection->table('v_class_roster')
            ->where('teacher_id', $teacher->nip)
            ->orderBy('class_name')
            ->orderBy('student_name')
            ->get()
            ->groupBy('cst_id');
        $schedule = $connection->table('v_teaching_schedule')
            ->where('teacher_id', $teacher->nip)
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        $allExams = \App\Models\Exam::whereIn('cst_id', $cstIds)
            ->with(['classSubjectTeacher.schoolClass', 'classSubjectTeacher.subject'])
            ->withCount('questions')
            ->latest('created_at')
            ->get();
        $recentExams = $allExams->take(6);

        $totalMaterials = \App\Models\Material::whereIn('cst_id', $cstIds)->count();

        return view('guru.dashboard', compact(
            'overview',
            'gradeSummaries',
            'gradebook',
            'assignmentStatuses',
            'roster',
            'schedule',
            'allExams',
            'recentExams',
            'totalMaterials'
        ));
    }
}
