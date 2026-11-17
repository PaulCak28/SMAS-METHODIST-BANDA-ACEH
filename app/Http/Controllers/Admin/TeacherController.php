<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $query = Teacher::on('mysql_admin')->with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nip', 'like', "%{$search}%")
                  ->orWhere('specialization', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $teachers = $query->orderBy('nip')->paginate(15);

        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('admin.teachers.create');
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'name'            => 'required|string|max:100',
            'email'           => 'required|email|unique:mysql_admin.users,email',
            'phone'           => 'nullable|string|max:20',
            'nip'             => 'required|string|max:18|unique:mysql_admin.teachers,nip|unique:mysql_admin.users,user_id',
            'specialization'  => 'nullable|string|max:100',
            'education_level' => 'nullable|string|max:30',
            'address'         => 'nullable|string|max:255',
            'hire_date'       => 'nullable|date',
            'status'          => ['nullable', Rule::in(['aktif', 'cuti', 'pensiun', 'resign'])],
        ]);

        DB::connection('mysql_admin')->transaction(function () use ($v) {
            User::on('mysql_admin')->create([
                'user_id'  => $v['nip'],
                'name'     => $v['name'],
                'email'    => $v['email'],
                'phone'    => $v['phone'] ?? null,
                'password' => Hash::make('password123'),
                'role'     => 'guru',
            ]);

            Teacher::on('mysql_admin')->create([
                'nip'             => $v['nip'],
                'specialization'  => $v['specialization'] ?? null,
                'education_level' => $v['education_level'] ?? null,
                'address'         => $v['address'] ?? null,
                'hire_date'       => $v['hire_date'] ?? null,
                'status'          => $v['status'] ?? 'aktif',
            ]);
        });

        return redirect()
            ->route('admin.teachers.index')
            ->with('success', 'Guru berhasil ditambahkan. Password default: password123');
    }

    public function edit(string $nip)
    {
        $teacher = Teacher::on('mysql_admin')->with('user')->findOrFail($nip);

        return view('admin.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, string $nip)
    {
        $teacher = Teacher::on('mysql_admin')->with('user')->findOrFail($nip);

        $v = $request->validate([
            'name'            => 'required|string|max:100',
            'email'           => [
                'required',
                'email',
                Rule::unique('mysql_admin.users', 'email')->ignore($teacher->nip, 'user_id'),
            ],
            'phone'           => 'nullable|string|max:20',
            'specialization'  => 'nullable|string|max:100',
            'education_level' => 'nullable|string|max:30',
            'address'         => 'nullable|string|max:255',
            'hire_date'       => 'nullable|date',
            'status'          => ['nullable', Rule::in(['aktif', 'cuti', 'pensiun', 'resign'])],
        ]);

        DB::connection('mysql_admin')->transaction(function () use ($v, $teacher) {
            $teacher->update([
                'specialization'  => $v['specialization'] ?? null,
                'education_level' => $v['education_level'] ?? null,
                'address'         => $v['address'] ?? null,
                'hire_date'       => $v['hire_date'] ?? null,
                'status'          => $v['status'] ?? $teacher->status,
            ]);

            if ($teacher->user) {
                $teacher->user->update([
                    'name'  => $v['name'],
                    'email' => $v['email'],
                    'phone' => $v['phone'] ?? null,
                ]);
            }
        });

        return redirect()
            ->route('admin.teachers.index')
            ->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy(string $nip)
    {
        $teacher = Teacher::on('mysql_admin')->with('user')->findOrFail($nip);

        DB::connection('mysql_admin')->transaction(function () use ($teacher) {
            $teacher->delete();
            $teacher->user?->delete();
        });

        return redirect()
            ->route('admin.teachers.index')
            ->with('success', 'Guru berhasil dihapus.');
    }
}