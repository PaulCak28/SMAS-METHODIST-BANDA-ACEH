<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SchoolClassController extends Controller
{
    public function index(Request $request)
    {
        $query = SchoolClass::with('homeroomTeacher.user')
            ->withCount('students');

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('academic_year', 'like', "%{$term}%")
                  ->orWhere('homeroom_teacher_id', 'like', "%{$term}%")
                  ->orWhereHas('homeroomTeacher.user', function ($u) use ($term) {
                      $u->where('name', 'like', "%{$term}%");
                  });
            });
        }

        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        $classes = $query->orderBy('name')->paginate(15)->withQueryString();

        $academicYears = SchoolClass::whereNotNull('academic_year')
            ->distinct()
            ->orderByDesc('academic_year')
            ->pluck('academic_year');

        $classIds = $classes->pluck('class_id')->all();

        $studentsByClass = DB::connection('mysql_admin')
            ->table('students as s')
            ->leftJoin('users as u', 'u.user_id', '=', 's.nis')
            ->whereIn('s.class_id', $classIds)
            ->select(
                's.class_id',
                's.nis',
                's.nisn',
                'u.name as student_name',
                's.gender',
                's.status'
            )
            ->orderBy('u.name')
            ->get()
            ->groupBy(fn ($item) => (string) $item->class_id);

        return view('admin.classes.index', [
            'classes'         => $classes,
            'academicYears'   => $academicYears,
            'studentsByClass' => $studentsByClass,
        ]);
    }

    public function create()
    {
        $teachers = Teacher::with('user')->get();

        return view('admin.classes.create', compact('teachers'));
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'name' => 'required|string|max:50',

            'homeroom_teacher_id' => [
                'nullable',
                'exists:teachers,nip'
            ],

            'academic_year' => [
                'required',
                'string',
                'max:9'
            ],
        ]);

        // class_id tidak auto-increment, jadi dibuat berurutan
        $v['class_id'] = (SchoolClass::max('class_id') ?? 100) + 1;
        (new SchoolClass)->forceFill($v)->save();

        return redirect()
            ->route('admin.classes.index')
            ->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit(SchoolClass $class)
    {
        $teachers = Teacher::with('user')->get();

        return view('admin.classes.edit', [
            'schoolClass' => $class,
            'teachers' => $teachers
        ]);
    }

    public function update(Request $request, SchoolClass $class)
    {
        $v = $request->validate([
            'name' => 'required|string|max:50',

            'homeroom_teacher_id' => [
                'nullable',
                'exists:teachers,nip'
            ],

            'academic_year' => [
                'required',
                'string',
                'max:9'
            ],
        ]);

        $class->update($v);

        return redirect()
            ->route('admin.classes.index')
            ->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(SchoolClass $class)
    {
        $class->delete();

        return redirect()
            ->route('admin.classes.index')
            ->with('success', 'Kelas berhasil dihapus.');
    }
}