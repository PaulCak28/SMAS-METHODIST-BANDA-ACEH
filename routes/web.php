
<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\Admin\AnnouncementImageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\MonitoringController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\ClassEnrollmentController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\CstController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\AnnouncementController as AdminAnnouncement;
use App\Http\Controllers\Guru\ClassroomController as GuruClassroom;
use App\Http\Controllers\Guru\DashboardController as GuruDashboard;
use App\Http\Controllers\Guru\ExamController as GuruExam;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Siswa\ClassroomController as SiswaClassroom;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboard;
use App\Http\Controllers\Siswa\ExamController as SiswaExam;
use Illuminate\Support\Facades\Route;

// =========================================================
// HALAMAN UMUM
// =========================================================

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('/pengumuman', [AnnouncementController::class, 'index'])
    ->name('announcements.index');

Route::get('/pengumuman/{announcement:slug}', [AnnouncementController::class, 'show'])
    ->name('announcements.show');


// =========================================================
// ROUTE YANG MEMERLUKAN LOGIN
// =========================================================

Route::middleware('auth')->group(function () {

    // =====================================================
    // DASHBOARD
    // =====================================================

    Route::get('/dashboard', function () {
        // Pengalihan dashboard ditangani middleware role.redirect.
    })->middleware('role.redirect')->name('dashboard');


    // =====================================================
    // SISWA
    // =====================================================

    Route::get('/siswa', [SiswaDashboard::class, 'index'])
        ->name('siswa.dashboard');

    // E-Class Siswa
    Route::prefix('siswa/e-class')->name('siswa.classes.')->group(function () {

        Route::get('/', [SiswaClassroom::class, 'index'])
            ->name('index');

        Route::post('/join', [SiswaClassroom::class, 'join'])
            ->name('join');

        Route::get('/{cst}', [SiswaClassroom::class, 'show'])
            ->name('show');

        Route::post('/assignments/{assignment}/submit', [SiswaClassroom::class, 'submit'])
            ->name('assignments.submit');
    });

    // E-Exam Siswa
    Route::prefix('siswa/e-exam')->name('siswa.exams.')->group(function () {

        Route::get('/', [SiswaExam::class, 'index'])
            ->name('index');

        Route::get('/{exam}', [SiswaExam::class, 'show'])
            ->name('show');

        Route::post('/{exam}/start', [SiswaExam::class, 'start'])
            ->name('start');

        Route::get('/{exam}/take', [SiswaExam::class, 'take'])
            ->name('take');

        Route::post('/{exam}/answer', [SiswaExam::class, 'answer'])
            ->name('answer');

        Route::post('/{exam}/essay/{question}', [SiswaExam::class, 'essay'])
            ->name('essay');

        Route::post('/{exam}/violation', [SiswaExam::class, 'violation'])
            ->name('violation');

        Route::post('/{exam}/leave', [SiswaExam::class, 'leave'])
            ->name('leave');

        Route::post('/{exam}/finish', [SiswaExam::class, 'finish'])
            ->name('finish');
    });


    // =====================================================
    // GURU
    // =====================================================

    Route::get('/guru', [GuruDashboard::class, 'index'])
        ->name('guru.dashboard');


    // =====================================================
    // E-CLASS GURU
    // =====================================================

    Route::prefix('guru/e-class')->name('guru.classes.')->group(function () {

    // Daftar kelas guru
    Route::get('/', [GuruClassroom::class, 'index'])
        ->name('index');

    // Detail kelas
    Route::get('/{cst}', [GuruClassroom::class, 'show'])
        ->name('show');

    // Membuat ulang kode gabung kelas
    Route::post('/{cst}/regenerate-code', [GuruClassroom::class, 'regenerateCode'])
        ->name('regenerate-code');

    // Mengeluarkan siswa
    Route::delete('/{cst}/students/{nis}', [GuruClassroom::class, 'removeStudent'])
        ->name('students.remove');

    // Upload gambar editor materi dan tugas
    Route::post('/{cst}/editor-images', [GuruClassroom::class, 'uploadEditorImage'])
        ->name('editor-images.store');

    // MATERI
    Route::post('/{cst}/materials', [GuruClassroom::class, 'storeMaterial'])
        ->name('materials.store');

    Route::put('/{cst}/materials/{material}', [GuruClassroom::class, 'updateMaterial'])
        ->name('materials.update');

    Route::delete('/{cst}/materials/{material}', [GuruClassroom::class, 'destroyMaterial'])
        ->name('materials.destroy');

    // TUGAS
    Route::post('/{cst}/assignments', [GuruClassroom::class, 'storeAssignment'])
        ->name('assignments.store');

    Route::put('/assignments/{assignment}', [GuruClassroom::class, 'updateAssignment'])
        ->name('assignments.update');

    Route::get('/assignments/{assignment}', [GuruClassroom::class, 'showAssignment'])
        ->name('assignments.show');

    Route::delete('/assignments/{assignment}', [GuruClassroom::class, 'destroyAssignment'])
        ->name('assignments.destroy');

    Route::patch('/assignments/{assignment}/students/{nis}/grade', [GuruClassroom::class, 'gradeSubmission'])
        ->name('assignments.grade');

    Route::get('/assignments/{assignment}/students/{nis}/download', [GuruClassroom::class, 'downloadSubmission'])
        ->name('assignments.download');
    });


    // =====================================================
    // E-EXAM GURU
    // =====================================================

    Route::prefix('guru/e-exam')->name('guru.exams.')->group(function () {

        // Daftar ujian
        Route::get('/', [GuruExam::class, 'index'])
            ->name('index');

        // Membuat ujian
        Route::get('/create', [GuruExam::class, 'create'])
            ->name('create');

        Route::post('/', [GuruExam::class, 'store'])
            ->name('store');

        // Detail ujian
        Route::get('/{exam}', [GuruExam::class, 'show'])
            ->name('show');

        // Edit ujian
        Route::get('/{exam}/edit', [GuruExam::class, 'edit'])
            ->name('edit');

        Route::put('/{exam}', [GuruExam::class, 'update'])
            ->name('update');

        // Hapus ujian
        Route::delete('/{exam}', [GuruExam::class, 'destroy'])
            ->name('destroy');

        // Publikasi ujian
        Route::patch('/{exam}/toggle-publish', [GuruExam::class, 'togglePublish'])
            ->name('toggle-publish');

        // Tambah soal
        Route::post('/{exam}/questions', [GuruExam::class, 'storeQuestion'])
            ->name('questions.store');

        // Edit soal
        Route::put('/{exam}/questions/{question}', [GuruExam::class, 'updateQuestion'])
            ->name('questions.update');

        // Update nilai soal
        Route::patch('/{exam}/questions/{question}/score', [GuruExam::class, 'updateScore'])
            ->name('questions.update-score');

        // Hapus soal
        Route::delete('/{exam}/questions/{question}', [GuruExam::class, 'destroyQuestion'])
            ->name('questions.destroy');

        // Hasil ujian
        Route::get('/{exam}/results', [GuruExam::class, 'results'])
            ->name('results');

        // Detail sesi ujian
        Route::get('/session/{session}', [GuruExam::class, 'session'])
            ->name('session');

        // Koreksi esai
        Route::patch('/session/{session}/grade-essay', [GuruExam::class, 'gradeEssay'])
            ->name('grade-essay');
    });


    // =====================================================
    // ADMIN
    // =====================================================

    Route::get('/admin/dashboard', [AdminDashboard::class, 'index'])
        ->name('admin.dashboard');


    // -----------------------------
    // MONITORING
    // -----------------------------

    Route::prefix('admin/monitoring')->name('admin.monitoring.')->group(function () {

        Route::get('/', [MonitoringController::class, 'index'])
            ->name('index');

        Route::get('/session/{id}', [MonitoringController::class, 'show'])
            ->name('show');
    });


    // -----------------------------
    // ACCOUNT MANAGEMENT
    // -----------------------------

    Route::prefix('admin/accounts')->name('admin.accounts.')->group(function () {

        Route::get('/', [AccountController::class, 'index'])
            ->name('index');

        Route::get('/create', [AccountController::class, 'create'])
            ->name('create');

        Route::post('/', [AccountController::class, 'store'])
            ->name('store');

        Route::get('/{id}/edit', [AccountController::class, 'edit'])
            ->name('edit');

        Route::put('/{id}', [AccountController::class, 'update'])
            ->name('update');

        Route::delete('/{id}', [AccountController::class, 'destroy'])
            ->name('destroy');
    });


    // -----------------------------
    // SUBJECT MANAGEMENT
    // -----------------------------

    Route::prefix('admin/subjects')->name('admin.subjects.')->group(function () {

        Route::get('/', [SubjectController::class, 'index'])
            ->name('index');

        Route::get('/create', [SubjectController::class, 'create'])
            ->name('create');

        Route::post('/', [SubjectController::class, 'store'])
            ->name('store');

        Route::get('/{id}/edit', [SubjectController::class, 'edit'])
            ->name('edit');

        Route::put('/{id}', [SubjectController::class, 'update'])
            ->name('update');

        Route::delete('/{id}', [SubjectController::class, 'destroy'])
            ->name('destroy');
    });


    // -----------------------------
    // CLASS SUBJECT TEACHER
    // -----------------------------

    Route::prefix('admin/cst')->name('admin.cst.')->group(function () {

        Route::get('/', [CstController::class, 'index'])
            ->name('index');

        Route::get('/create', [CstController::class, 'create'])
            ->name('create');

        Route::post('/', [CstController::class, 'store'])
            ->name('store');

        Route::get('/{cst}/edit', [CstController::class, 'edit'])
            ->name('edit');

        Route::put('/{cst}', [CstController::class, 'update'])
            ->name('update');

        Route::delete('/{cst}', [CstController::class, 'destroy'])
            ->name('destroy');
    });


    // -----------------------------
    // CLASS ENROLLMENT
    // -----------------------------

Route::prefix('admin/announcements')
        ->name('admin.announcements.')
        ->middleware(['auth', 'role:admin'])
        ->group(function () {
            Route::get('/', [AdminAnnouncement::class, 'index'])->name('index');
            Route::get('/create', [AdminAnnouncement::class, 'create'])->name('create');
            Route::post('/', [AdminAnnouncement::class, 'store'])->name('store');

            // Upload gambar pengumuman (maksimal 30 request per menit)
            Route::post('/images', [AnnouncementImageController::class, 'store'])
                ->middleware('throttle:30,1')
                ->name('images.store');

            Route::get('/{announcement}', [AdminAnnouncement::class, 'show'])->name('show');
            Route::get('/{announcement}/edit', [AdminAnnouncement::class, 'edit'])->name('edit');
            Route::put('/{announcement}', [AdminAnnouncement::class, 'update'])->name('update');
            Route::delete('/{announcement}', [AdminAnnouncement::class, 'destroy'])->name('destroy');
        });

    // Tambah enrollment dari halaman siswa
    Route::post('/admin/students/{student}/enrollments', [ClassEnrollmentController::class, 'storeFromStudent'])
        ->name('admin.students.enrollments.store');


    // -----------------------------
    // STUDENT MANAGEMENT
    // -----------------------------

    Route::prefix('admin/students')->name('admin.students.')->group(function () {

        Route::get('/', [StudentController::class, 'index'])
            ->name('index');

        Route::get('/create', [StudentController::class, 'create'])
            ->name('create');

        Route::post('/', [StudentController::class, 'store'])
            ->name('store');

        Route::get('/{student}/edit', [StudentController::class, 'edit'])
            ->name('edit');

        Route::put('/{student}', [StudentController::class, 'update'])
            ->name('update');

        Route::delete('/{student}', [StudentController::class, 'destroy'])
            ->name('destroy');

        Route::get('/{student}', [StudentController::class, 'show'])
            ->name('show');
    });


    // -----------------------------
    // TEACHER MANAGEMENT
    // -----------------------------

    Route::prefix('admin/teachers')->name('admin.teachers.')->group(function () {

        Route::get('/', [TeacherController::class, 'index'])
            ->name('index');

        Route::get('/create', [TeacherController::class, 'create'])
            ->name('create');

        Route::post('/', [TeacherController::class, 'store'])
            ->name('store');

        Route::get('/{teacher}/edit', [TeacherController::class, 'edit'])
            ->name('edit');

        Route::put('/{teacher}', [TeacherController::class, 'update'])
            ->name('update');

        Route::delete('/{teacher}', [TeacherController::class, 'destroy'])
            ->name('destroy');
    });

    // =====================================================
    // PROFILE
    // =====================================================

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

});


// =========================================================
// AUTHENTICATION ROUTES
// =========================================================

require __DIR__ . '/auth.php';
