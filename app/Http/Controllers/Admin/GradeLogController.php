<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GradeLog;
use Illuminate\Http\Request;

class GradeLogController extends Controller
{
    public function index(Request $request)
    {
        $query = GradeLog::with('student');

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('students_id', 'like', "%{$search}%")
                  ->orWhere('cst_id', 'like', "%{$search}%")
                  ->orWhere('grade_id', 'like', "%{$search}%")
                  ->orWhere('db_user', 'like', "%{$search}%");
            });
        }

        $logs = $query->orderByDesc('log_id')->paginate(20)->withQueryString();

        return view('admin.activity-logs.index', compact('logs'));
    }
}