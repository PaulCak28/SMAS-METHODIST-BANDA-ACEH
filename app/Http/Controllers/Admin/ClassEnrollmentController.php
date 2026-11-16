<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassEnrollment;
use App\Models\ClassSubjectTeacher;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ClassEnrollmentController extends Controller
{
    private const STATUSES = ['aktif', 'naik_kelas', 'tinggal_kelas', 'pindah', 'lulus', 'drop_out'];

    /**
     * Menampilkan daftar siswa secara unik (1 baris per siswa yang memiliki enrollment).
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $students = Student::with(['user', 'schoolClass', 'classEnrollments'])
            ->has('classEnrollments') // Hanya tampilkan siswa yang sudah terdaftar di kelas mapel
            ->when($search, function ($query, $search) {
                // Dikelompokkan agar "has classEnrollments" tetap berlaku saat mencari
                $query->where(function ($q) use ($search) {
                    $q->where('nis', 'like', "%{$search}%")
                        ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"));
                });
            })
            ->latest('nis')
            ->paginate(15)
            ->withQueryString();

        // Daftar kelas-mapel untuk modal "Masukkan Satu Kelas"
        $csts = ClassSubjectTeacher::with('subject', 'schoolClass')->orderBy('class_id')->get();

        // Untuk modal "Masukkan Satu Kelas": siswa per kelas induk & siswa yang sudah terdaftar per kelas-mapel
        $studentsByClass = Student::with('user')
            ->whereNotNull('class_id')
            ->orderBy('nis')
            ->get()
            ->groupBy('class_id');

        $enrolledByCst = ClassEnrollment::select('cst_id', 'students_id')
            ->get()
            ->groupBy('cst_id')
            ->map(fn ($rows) => $rows->pluck('students_id')->values());

        return view('admin.enrollments.index', compact('students', 'search', 'csts', 'studentsByClass', 'enrolledByCst'));
    }

    /**
     * Menampilkan rincian seluruh kelas mapel yang diikuti oleh satu siswa tertentu.
     */
    public function show($nis)
    {
        $student = Student::with([
            'user',
            'schoolClass',
            'classEnrollments.classSubjectTeacher.subject',
            'classEnrollments.classSubjectTeacher.schoolClass',
            'classEnrollments.classSubjectTeacher.teacher.user',
        ])->where('nis', $nis)->firstOrFail();

        // Data CST yang tersedia untuk pilihan modal tambah kelas
        $csts = ClassSubjectTeacher::with('subject', 'schoolClass')->orderBy('class_id')->get();

        return view('admin.enrollments.show', compact('student', 'csts'));
    }

    public function create()
    {
        $students = Student::with('user')->orderBy('nis')->get();
        $csts = ClassSubjectTeacher::with('subject', 'schoolClass')->orderBy('class_id')->get();

        return view('admin.enrollments.create', compact('students', 'csts'));
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'students_id' => ['required', 'exists:students,nis'],
            'cst_id' => [
                'required',
                'exists:class_subject_teacher,cst_id',
                Rule::unique('class_enrollments')->where(
                    fn ($q) => $q->where('students_id', $request->students_id)
                ),
            ],
            'status' => ['required', Rule::in(self::STATUSES)],
            'enrolled_at' => 'nullable|date',
            'left_at' => 'nullable|date|after_or_equal:enrolled_at',
        ]);

        $cst = ClassSubjectTeacher::findOrFail($v['cst_id']);

        DB::transaction(function () use ($v, $cst) {
            ClassEnrollment::create(
                $v + ['academic_year' => $cst->academic_year]
            );

            $membershipStatus = match ($v['status']) {
                'aktif' => 'aktif',
                'lulus' => 'selesai',
                default => 'keluar',
            };

            DB::table('cst_students')->updateOrInsert(
                [
                    'cst_id' => $cst->cst_id,
                    'students_id' => $v['students_id'],
                ],
                [
                    'status' => $membershipStatus,
                    'joined_at' => $v['enrolled_at'] ?? now(),
                    'left_at' => $membershipStatus === 'aktif'
                        ? null
                        : ($v['left_at'] ?? now()),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            $this->syncCurrentClass(
                $v['students_id'],
                $cst,
                $v['status']
            );
        });

        return redirect()
            ->route('admin.enrollments.show', $v['students_id'])
            ->with('success', 'Siswa berhasil dimasukkan ke kelas mapel.');
    }

    public function storeBulk(Request $request)
    {
        $v = $request->validate([
            'cst_id' => ['required', 'exists:class_subject_teacher,cst_id'],
            'students_id' => ['required', 'array', 'min:1'],
            'students_id.*' => ['exists:students,nis'],
        ], [
            'students_id.required' => 'Pilih minimal satu siswa.',
            'students_id.min' => 'Pilih minimal satu siswa.',
        ]);

        $cst = ClassSubjectTeacher::findOrFail($v['cst_id']);

        $nisList = Student::where('class_id', $cst->class_id)
            ->whereIn('nis', $v['students_id'])
            ->pluck('nis');

        $alreadyEnrolled = ClassEnrollment::where('cst_id', $cst->cst_id)
            ->whereIn('students_id', $nisList)
            ->pluck('students_id');

        $toEnroll = $nisList->diff($alreadyEnrolled);

        if ($toEnroll->isEmpty()) {
            return back()->withInput()->withErrors([
                'students_id' => 'Semua siswa yang dipilih sudah terdaftar di kelas mapel ini.',
            ]);
        }

        DB::transaction(function () use ($toEnroll, $cst) {
            foreach ($toEnroll as $nis) {
                $enrolledAt = now();

                ClassEnrollment::create([
                    'students_id' => $nis,
                    'cst_id' => $cst->cst_id,
                    'status' => 'aktif',
                    'enrolled_at' => $enrolledAt->toDateString(),
                    'academic_year' => $cst->academic_year,
                ]);

                DB::table('cst_students')->updateOrInsert(
                    [
                        'cst_id' => $cst->cst_id,
                        'students_id' => $nis,
                    ],
                    [
                        'status' => 'aktif',
                        'joined_at' => $enrolledAt,
                        'left_at' => null,
                        'created_at' => $enrolledAt,
                        'updated_at' => $enrolledAt,
                    ]
                );
            }
        });

        $message = "{$toEnroll->count()} siswa berhasil dimasukkan ke kelas mapel.";

        if ($alreadyEnrolled->isNotEmpty()) {
            $message .= " {$alreadyEnrolled->count()} siswa dilewati karena sudah terdaftar.";
        }

        return redirect()
            ->route('admin.enrollments.index')
            ->with('success', $message);
    }

    public function edit(ClassEnrollment $enrollment)
    {
        $enrollment->load('student.user');
        $csts = ClassSubjectTeacher::with('subject', 'schoolClass')->orderBy('class_id')->get();

        return view('admin.enrollments.edit', compact('enrollment', 'csts'));
    }

    public function update(Request $request, ClassEnrollment $enrollment)
    {
        $v = $request->validate([
            'cst_id' => [
                'required',
                'exists:class_subject_teacher,cst_id',
                Rule::unique('class_enrollments')
                    ->where(fn ($q) => $q->where('students_id', $enrollment->students_id))
                    ->ignore($enrollment->enrollment_id, 'enrollment_id'),
            ],
            'status' => ['required', Rule::in(self::STATUSES)],
            'enrolled_at' => 'nullable|date',
            'left_at' => 'nullable|date|after_or_equal:enrolled_at',
        ]);

        $cst = ClassSubjectTeacher::findOrFail($v['cst_id']);
        $oldCstId = $enrollment->cst_id;
        $nis = $enrollment->students_id;

        DB::transaction(function () use ($v, $cst, $enrollment, $oldCstId, $nis) {
            // Jika kelas mapel berubah, keluarkan dari keanggotaan kelas sebelumnya.
            if ($oldCstId !== $cst->cst_id) {
                DB::table('cst_students')
                    ->where('cst_id', $oldCstId)
                    ->where('students_id', $nis)
                    ->update([
                        'status' => 'keluar',
                        'left_at' => now(),
                        'updated_at' => now(),
                    ]);
            }

            $enrollment->update(
                $v + ['academic_year' => $cst->academic_year]
            );

            $membershipStatus = match ($v['status']) {
                'aktif' => 'aktif',
                'lulus' => 'selesai',
                default => 'keluar',
            };

            $membership = DB::table('cst_students')
                ->where('cst_id', $cst->cst_id)
                ->where('students_id', $nis);

            $membershipData = [
                'status' => $membershipStatus,
                'joined_at' => $v['enrolled_at'] ?? now(),
                'left_at' => $membershipStatus === 'aktif'
                    ? null
                    : ($v['left_at'] ?? now()),
                'updated_at' => now(),
            ];

            if ($membership->exists()) {
                $membership->update($membershipData);
            } else {
                DB::table('cst_students')->insert(
                    $membershipData + [
                        'cst_id' => $cst->cst_id,
                        'students_id' => $nis,
                        'created_at' => now(),
                    ]
                );
            }

            $this->syncCurrentClass($nis, $cst, $v['status']);
        });

        return redirect()
            ->route('admin.enrollments.show', $nis)
            ->with('success', 'Data kelas siswa berhasil diperbarui.');
    }

    public function destroy(ClassEnrollment $enrollment)
    {
        $studentNis = $enrollment->students_id;
        $cstId = $enrollment->cst_id;
    
        DB::transaction(function () use ($enrollment, $studentNis, $cstId) {
            DB::table('cst_students')
                ->where('cst_id', $cstId)
                ->where('students_id', $studentNis)
                ->delete();
    
            $enrollment->delete();
        });
    
        return redirect()
            ->route('admin.enrollments.show', $studentNis)
            ->with('success', 'Siswa berhasil dikeluarkan dari kelas mapel.');
    }

    /**
     * Pendaftaran berstatus "aktif" menentukan kelas induk siswa saat ini
     * (students.class_id), diambil dari class_id milik cst.
     */
    private function syncCurrentClass(int|string $nis, ClassSubjectTeacher $cst, string $status): void
    {
        if ($status === 'aktif') {
            Student::where('nis', $nis)->update(['class_id' => $cst->class_id]);
        }
    }
}