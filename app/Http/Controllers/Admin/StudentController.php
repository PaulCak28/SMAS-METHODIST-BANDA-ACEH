<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::on('mysql_admin')->with(['user', 'schoolClass']);

        if ($request->filled('search')) {
            $search =$request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nis', 'like', "\%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('parent_name', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "\%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('class_id')) {
            if ($request->class_id === 'unassigned') {$query->whereNull('class_id');
            } else {
                $query->where('class_id',$request->class_id);
            }
        }

        if ($request->filled('status')) {
            $query->where('status',$request->status);
        }

        $students = $query->orderBy('nis')->paginate(15);$classes  = SchoolClass::on('mysql_admin')->orderBy('name')->get();

        return view('admin.students.index', compact('students', 'classes'));
    }

    public function create()
    {
        $classes = SchoolClass::on('mysql_admin')->orderBy('name')->get();

        return view('admin.students.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $v =$request->validate([
            'name'         => 'required|string|max:100',
            'email'        => 'required|email|unique:mysql_admin.users,email',
            'phone'        => 'nullable|string|max:20',
            'nis'          => 'required|string|max:10|unique:mysql_admin.students,nis|unique:mysql_admin.users,user_id',
            'nisn'         => 'nullable|digits:10|unique:mysql_admin.students,nisn',
            'class_id'     => ['nullable', 'exists:mysql_admin.classes,class_id'],
            'gender'       => 'required|in:L,P',
            'pob'          => 'nullable|string|max:50',
            'dob'          => 'nullable|date',
            'address'      => 'nullable|string|max:255',
            'district'     => 'nullable|string|max:100',
            'city'         => 'nullable|string|max:100',
            'province'     => 'nullable|string|max:100',
            'parent_name'  => 'nullable|string|max:100',
            'parent_phone' => 'nullable|string|max:20',
            'entry_year'   => 'nullable|digits:4',
            'status'       => ['nullable', Rule::in(['aktif', 'lulus', 'pindah', 'drop_out'])],
        ]);

        DB::connection('mysql_admin')->transaction(function () use ($v) {
            User::on('mysql_admin')->create([
                'user_id'  => $v['nis'],
                'name'     => $v['name'],
                'email'    => $v['email'],
                'phone'    => $v['phone'] ?? null,
                'password' => Hash::make('password123'),
                'role'     => 'siswa',
            ]);

            Student::on('mysql_admin')->create([
                'nis'          => $v['nis'],
                'nisn'         => $v['nisn'] ?? null,
                'class_id'     => $v['class_id'] ?? null,
                'gender'       => $v['gender'],
                'pob'          => $v['pob'] ?? null,
                'dob'          => $v['dob'] ?? null,
                'address'      => $v['address'] ?? null,
                'district'     => $v['district'] ?? null,
                'city'         => $v['city'] ?? null,
                'province'     => $v['province'] ?? null,
                'parent_name'  => $v['parent_name'] ?? null,
                'parent_phone' => $v['parent_phone'] ?? null,
                'entry_year'   => $v['entry_year'] ?? null,
                'status'       => $v['status'] ?? 'aktif',
            ]);
        });

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Siswa berhasil ditambahkan. Password default: password123');
    }

    public function edit(string $nis)
    {
        $student = Student::on('mysql_admin')->with('user')->findOrFail($nis);$classes = SchoolClass::on('mysql_admin')->orderBy('name')->get();

        return view('admin.students.edit', compact('student', 'classes'));
    }

    public function update(Request $request, string$nis)
    {
        $student = Student::on('mysql_admin')->with('user')->findOrFail($nis);

        $v =$request->validate([
            'name'         => 'required|string|max:100',
            'email'        => [
                'required',
                'email',
                Rule::unique('mysql_admin.users', 'email')->ignore($student->nis, 'user_id'),
            ],
            'phone'        => 'nullable|string|max:20',
            'nisn'         => [
                'nullable',
                'digits:10',
                Rule::unique('mysql_admin.students', 'nisn')->ignore($student->nis, 'nis'),
            ],
            'class_id'     => ['nullable', 'exists:mysql_admin.classes,class_id'],
            'gender'       => 'required|in:L,P',
            'pob'          => 'nullable|string|max:50',
            'dob'          => 'nullable|date',
            'address'      => 'nullable|string|max:255',
            'district'     => 'nullable|string|max:100',
            'city'         => 'nullable|string|max:100',
            'province'     => 'nullable|string|max:100',
            'parent_name'  => 'nullable|string|max:100',
            'parent_phone' => 'nullable|string|max:20',
            'entry_year'   => 'nullable|digits:4',
            'status'       => ['nullable', Rule::in(['aktif', 'lulus', 'pindah', 'drop_out'])],
        ]);

        DB::connection('mysql_admin')->transaction(function () use ($v, $student) {$student->update([
                'nisn'         => $v['nisn'] ?? null,
                'class_id'     => $v['class_id'] ?? null,
                'gender'       => $v['gender'],
                'pob'          => $v['pob'] ?? null,
                'dob'          => $v['dob'] ?? null,
                'address'      => $v['address'] ?? null,
                'district'     => $v['district'] ?? null,
                'city'         => $v['city'] ?? null,
                'province'     => $v['province'] ?? null,
                'parent_name'  => $v['parent_name'] ?? null,
                'parent_phone' => $v['parent_phone'] ?? null,
                'entry_year'   => $v['entry_year'] ?? null,
                'status'       => $v['status'] ?? $student->status,
            ]);

            if ($student->user) {$student->user->update([
                    'name'  => $v['name'],
                    'email' => $v['email'],
                    'phone' => $v['phone'] ?? null,
                ]);
            }
        });

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(string $nis)
    {
        $student = Student::on('mysql_admin')->with('user')->findOrFail($nis);

        DB::connection('mysql_admin')->transaction(function () use ($student) {
            $student->delete();$student->user?->delete();
        });

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Siswa berhasil dihapus.');
    }
}