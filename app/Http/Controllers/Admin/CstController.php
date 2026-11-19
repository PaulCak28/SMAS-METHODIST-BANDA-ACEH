<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassSubjectTeacher;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CstController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $csts = ClassSubjectTeacher::with(['schoolClass', 'subject', 'teacher.user'])
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->whereHas('subject', fn($sub) => $sub->where('name', 'like', "%{$search}%"))
                      ->orWhereHas('schoolClass', fn($cls) => $cls->where('name', 'like', "%{$search}%"))
                      ->orWhereHas('teacher.user', fn($usr) => $usr->where('name', 'like', "%{$search}%"))
                      ->orWhere('join_code', 'like', "%{$search}%");
                });
            })
            ->latest('cst_id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.cst.index', compact('csts', 'search'));
    }

    public function create()
    {
        $rooms = SchoolClass::orderBy('name')->get(); 
        $subjects = Subject::orderBy('name')->get();
        $teachers = Teacher::with('user')->get();

        return view('admin.cst.create', compact('rooms', 'subjects', 'teachers'));
    }

    public function store(Request $request)
    {
        // [CONSISTENCY] Validasi input sesuai PK/FK tabel classes, subjects, dan teachers
        $v = $request->validate([
            'class_id' => 'required|exists:classes,class_id',
            'subject_code' => 'required|exists:subjects,code',
            'teacher_id' => 'required|exists:teachers,nip',
            'academic_year' => 'required|string|max:9',
        ]);

        try {
            // [ATOMICITY] Memulai transaksi database
            DB::beginTransaction();

            // [ISOLATION] Mengunci baris kelas & pengecekan duplikasi agar tidak bentrok saat akses bersamaan
            SchoolClass::where('class_id', $v['class_id'])->lockForUpdate()->first();

            $exists = ClassSubjectTeacher::where('class_id', $v['class_id'])
                ->where('subject_code', $v['subject_code'])
                ->where('academic_year', $v['academic_year'])
                ->lockForUpdate()
                ->exists();

            if ($exists) {
                DB::rollBack();
                return back()->withInput()->with('error', 'Kelas mata pelajaran ini sudah ada di ruang dan tahun ajaran tersebut.');
            }

            $cst = ClassSubjectTeacher::create($v);

            // Integrasi dengan objek database: jalankan sp_generate_join_code jika join_code masih kosong
            if (empty($cst->join_code)) {
                $generated = DB::select('CALL sp_generate_join_code(?)', [$cst->cst_id]);
                if (!empty($generated)) {
                    $cst->join_code = $generated[0]->join_code;
                }
            }

            // [DURABILITY] Menyimpan seluruh perubahan secara permanen ke disk
            DB::commit();

            return redirect()->route('admin.cst.index')
                ->with('success', 'Kelas mapel berhasil dibuat! Kode Gabung Kelas: ' . $cst->join_code);
        } catch (\Exception $e) {
            // [ATOMICITY] Batalkan seluruh perubahan jika terjadi kesalahan
            DB::rollBack();

            return back()->withInput()->with('error', 'Gagal membuat kelas mapel: ' . $e->getMessage());
        }
    }

    public function edit(ClassSubjectTeacher $cst)
    {
        $rooms = SchoolClass::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $teachers = Teacher::with('user')->get();

        return view('admin.cst.edit', compact('cst', 'rooms', 'subjects', 'teachers'));
    }

    public function update(Request $request, ClassSubjectTeacher $cst)
    {
        // [CONSISTENCY] Validasi struktur dan referential integrity
        $v = $request->validate([
            'class_id' => 'required|exists:classes,class_id',
            'subject_code' => 'required|exists:subjects,code',
            'teacher_id' => 'required|exists:teachers,nip',
            'academic_year' => 'required|string|max:9',
        ]);

        try {
            // [ATOMICITY] Memulai transaksi database
            DB::beginTransaction();

            // [ISOLATION] Mengunci baris data yang sedang diperbarui
            $lockedCst = ClassSubjectTeacher::where('cst_id', $cst->cst_id)->lockForUpdate()->first();

            $exists = ClassSubjectTeacher::where('class_id', $v['class_id'])
                ->where('subject_code', $v['subject_code'])
                ->where('academic_year', $v['academic_year'])
                ->where('cst_id', '!=', $cst->cst_id)
                ->lockForUpdate()
                ->exists();

            if ($exists) {
                DB::rollBack();
                return back()->withInput()->with('error', 'Perubahan dibatalkan karena akan menduplikat kelas yang sudah ada.');
            }

            // PERBAIKAN: Gunakan where() untuk mencocokkan primary key/kolom yang tepat
            $class = SchoolClass::where('class_id', $v['class_id'])->first();
            $subject = Subject::where('code', $v['subject_code'])->first();
            
            if ($class && $subject) {
                $cleanClass = preg_replace('/\s+/', '', $class->name);
                $v['join_code'] = strtoupper($subject->code . $cleanClass);
            }

            $lockedCst->update($v);

            // [DURABILITY] Komit perubahan ke database
            DB::commit();

            return redirect()->route('admin.cst.index')
                ->with('success', 'Data kelas mapel berhasil diperbarui.');
        } catch (\Exception $e) {
            // [ATOMICITY] Rollback jika terjadi error saat pembaruan
            DB::rollBack();

            return back()->withInput()->with('error', 'Gagal memperbarui data kelas mapel: ' . $e->getMessage());
        }
    }

    public function destroy(ClassSubjectTeacher $cst)
    {
        try {
            // [ATOMICITY] Memulai transaksi penghapusan
            DB::beginTransaction();

            // [ISOLATION] Mengunci baris sebelum dihapus untuk mencegah race condition dengan pendaftar baru
            $lockedCst = ClassSubjectTeacher::where('cst_id', $cst->cst_id)->lockForUpdate()->first();
            
            if ($lockedCst) {
                $lockedCst->delete();
            }

            // [DURABILITY] Konfirmasi penghapusan permanen
            DB::commit();

            return redirect()->route('admin.cst.index')->with('success', 'Kelas mapel berhasil dihapus.');
        } catch (\Exception $e) {
            // [ATOMICITY] Batalkan jika melanggar Foreign Key (misalnya masih ada data di cst_students / schedules / exams)
            DB::rollBack();

            return back()->with('error', 'Gagal menghapus! Kelas ini sudah memiliki siswa terdaftar (Enrollment).');
        }
    }
}