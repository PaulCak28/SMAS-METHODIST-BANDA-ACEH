<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $role = $request->input('role');

        $users = User::select('users.*')
            ->selectRaw("
                CASE
                    WHEN users.role = 'siswa' THEN (
                        SELECT AVG(fn_student_average(cs.students_id, cs.cst_id))
                        FROM cst_students cs
                        WHERE cs.students_id = users.user_id
                    )
                    ELSE NULL
                END AS student_avg_score
            ")
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('user_id', 'like', "%{$search}%");
                });
            })
            ->when($role, function ($query, $role) {
                return $query->where('role', $role);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.accounts.index', compact('users', 'search', 'role'));
    }

    public function create()
    {
        return view('admin.accounts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'  => 'required|string|max:18|unique:users,user_id',
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|max:255|unique:users,email',
            'role'     => 'required|in:admin,guru,siswa,orangtua',
            'password' => 'required|min:6',
        ]);

        if ($request->role === 'siswa' && strlen($request->user_id) > 10) {
            return back()->withErrors(['user_id' => 'User ID (NIS) untuk role Siswa maksimal 10 karakter.'])->withInput();
        }

        try {
            DB::beginTransaction();

            User::create([
                'user_id'  => $request->user_id,
                'name'     => $request->name,
                'email'    => $request->email,
                'role'     => $request->role,
                'password' => Hash::make($request->password),
            ]);

            if ($request->role === 'guru') {
                DB::table('teachers')->insert([
                    'nip'        => $request->user_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } elseif ($request->role === 'siswa') {
                DB::table('students')->insert([
                    'nis' => $request->user_id,
                ]);
            }

            DB::commit();

            return redirect()->route('admin.accounts.index')->with('success', 'Akun berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
{
    $user = User::where('user_id', $id)->firstOrFail();

    $gradeSummary = $user->role === 'siswa'
        ? DB::table('cst_students as cs')
            ->join('class_subject_teacher as cst', 'cst.cst_id', '=', 'cs.cst_id')
            ->join('subjects as s', 's.subject_id', '=', 'cst.subject_id')
            ->join('classes as c', 'c.class_id', '=', 'cst.class_id')
            ->where('cs.students_id', $user->user_id)
            ->select('s.name as subject_name', 'c.name as class_name')
            ->selectRaw('fn_student_average(cs.students_id, cs.cst_id) AS final_score')
            ->get()
        : collect();

    return view('admin.accounts.edit', compact('user', 'gradeSummary'));
}

    public function update(Request $request, $id)
    {
        $user = User::where('user_id', $id)->firstOrFail();

        $request->validate([
            'user_id'  => 'required|string|max:18|unique:users,user_id,' . $user->user_id . ',user_id',
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|max:255|unique:users,email,' . $user->user_id . ',user_id',
            'role'     => 'required|in:admin,guru,siswa,orangtua',
            'password' => 'nullable|min:6',
        ]);

        if ($request->role === 'siswa' && strlen($request->user_id) > 10) {
            return back()->withErrors(['user_id' => 'User ID (NIS) untuk role Siswa maksimal 10 karakter.'])->withInput();
        }

        try {
            DB::beginTransaction();

            $oldRole = $user->role;
            $oldId   = $user->user_id;

            $user->user_id = $request->user_id;
            $user->name    = $request->name;
            $user->email   = $request->email;
            $user->role    = $request->role;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            // Pastikan record relasi di teachers / students tetap sinkron jika role berubah
            if ($request->role === 'guru') {
                DB::table('teachers')->updateOrInsert(
                    ['nip' => $user->user_id],
                    ['updated_at' => now(), 'created_at' => now()]
                );
            } elseif ($request->role === 'siswa') {
                DB::table('students')->updateOrInsert(
                    ['nis' => $user->user_id],
                    ['nis' => $user->user_id]
                );
            }

            if ($oldRole !== $request->role) {
                if ($oldRole === 'guru') {
                    DB::table('teachers')->where('nip', $oldId)->delete();
                } elseif ($oldRole === 'siswa') {
                    DB::table('students')->where('nis', $oldId)->delete();
                }
            }

            DB::commit();

            return redirect()->route('admin.accounts.index')->with('success', 'Akun berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(string $id)
    {
        $user = User::where('user_id', $id)->firstOrFail();

        if ($user->user_id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        try {
            DB::beginTransaction();

            if ($user->role === 'guru') {
                DB::table('teachers')->where('nip', $user->user_id)->delete();
            } elseif ($user->role === 'siswa') {
                DB::table('students')->where('nis', $user->user_id)->delete();
            }

            $user->delete();

            DB::commit();

            return redirect()->route('admin.accounts.index')->with('success', 'Akun berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal menghapus akun: ' . $e->getMessage());
        }
    }
}