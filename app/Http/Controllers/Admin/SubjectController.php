<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassSubjectTeacher;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $search =$request->input('search');

        $subjects = Subject::on('mysql_admin')
            ->when($search, function ($query, $search) {$query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "\%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%");
                });
            })
            ->orderBy('name', 'asc')
            ->paginate(10)
            ->withQueryString();

        $cstTable = (new ClassSubjectTeacher())->getTable();$usageCounts = collect();

        if (Schema::connection('mysql_admin')->hasTable($cstTable)) {$codes = collect($subjects->items())->pluck('code');$usageCounts = DB::connection('mysql_admin')
                ->table($cstTable)
                ->whereIn('subject_code', $codes)
                ->select('subject_code', DB::raw('COUNT(*) as total'))
                ->groupBy('subject_code')
                ->pluck('total', 'subject_code');
        }

        return view('admin.subjects.index', compact('subjects', 'search', 'usageCounts'));
    }

    public function create()
    {
        return view('admin.subjects.create');
    }

    public function store(Request $request)
    {
        $v =$request->validate([
            'code' => 'required|string|max:20|unique:mysql_admin.subjects,code',
            'name' => 'required|string|max:255',
        ], [
            'code.unique' => 'Kode Mata Pelajaran ini sudah digunakan.',
        ]);

        Subject::on('mysql_admin')->create([
            'code' => strtoupper(trim($v['code'])),
            'name' => trim($v['name']),
        ]);

        return redirect()
            ->route('admin.subjects.index')
            ->with('success', 'Mata Pelajaran berhasil ditambahkan.');
    }

    public function edit(string $code)
    {
        $subject = Subject::on('mysql_admin')->where('code',$code)->firstOrFail();

        return view('admin.subjects.edit', compact('subject'));
    }

    public function update(Request $request, string$code)
    {
        $conn = DB::connection('mysql_admin');
        $subject = Subject::on('mysql_admin')->where('code',$code)->firstOrFail();

        $v =$request->validate([
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('mysql_admin.subjects', 'code')->ignore($subject->code, 'code'),
            ],
            'name' => 'required|string|max:255',
        ], [
            'code.unique' => 'Kode Mata Pelajaran ini sudah digunakan.',
        ]);

        $newCode = strtoupper(trim($v['code']));
        $newName = trim($v['name']);
        $oldCode =$subject->code;

        $conn->transaction(function () use ($conn,$oldCode, $newCode,$newName) {
            if ($oldCode === $newCode) {$conn->table('subjects')
                    ->where('code', $oldCode)
                    ->update(['name' => $newName]);
                return;
            }

            $cstTable = (new ClassSubjectTeacher())->getTable();
            $hasChildRows = Schema::connection('mysql_admin')->hasTable($cstTable)
                && $conn->table($cstTable)->where('subject_code',$oldCode)->exists();

            if (! $hasChildRows) {$conn->table('subjects')
                    ->where('code', $oldCode)
                    ->update([
                        'code' => $newCode,
                        'name' => $newName,
                    ]);
                return;
            }

            // Jika sudah dipakai di class_subject_teacher (meski FK tanpa ON UPDATE CASCADE),
            // buat kode baru -> pindahkan relasi -> hapus kode lama
            $conn->table('subjects')->insert([
                'code' => $newCode,
                'name' => $newName,
            ]);

            $conn->table($cstTable)
                ->where('subject_code', $oldCode)
                ->update(['subject_code' => $newCode]);

            $conn->table('subjects')
                ->where('code', $oldCode)
                ->delete();
        });

        return redirect()
            ->route('admin.subjects.index')
            ->with('success', 'Kode dan nama Mata Pelajaran berhasil diperbarui.');
    }

    public function destroy(string $code)
    {
        $subject = Subject::on('mysql_admin')->where('code',$code)->firstOrFail();

        try {
            $subject->delete();

            return redirect()
                ->route('admin.subjects.index')
                ->with('success', 'Mata Pelajaran berhasil dihapus.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal menghapus! Mata pelajaran ini sedang digunakan di relasi kelas/guru.');
        }
    }
}