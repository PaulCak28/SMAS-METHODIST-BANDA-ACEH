
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-sky-600">
                    Methodist Learning Space
                </p>
                <h2 class="text-xl font-bold tracking-tight text-slate-800">
                    Dashboard Guru
                </h2>
            </div>
            <span class="mt-2 inline-flex w-fit items-center gap-2 rounded-full bg-sky-50 px-4 py-2 text-xs font-semibold text-sky-700 sm:mt-0">
                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                Teacher Workspace
            </span>
        </div>
    </x-slot>

    @php
        $totalClasses = $overview->count();
        $totalStudents = $overview->sum('student_count');
        $totalAssignments = $overview->sum('assignment_count');
        $totalExams = $overview->sum('exam_count');
        $totalUngraded = $overview->sum('ungraded_essays');

        $subjectThemes = [
            'matematika' => ['icon' => '∑', 'name' => 'Mathematics', 'color' => 'blue'],
            'bahasa indonesia' => ['icon' => 'Aa', 'name' => 'Language', 'color' => 'rose'],
            'bahasa inggris' => ['icon' => 'Ab', 'name' => 'English', 'color' => 'indigo'],
            'biologi' => ['icon' => '✿', 'name' => 'Biology', 'color' => 'emerald'],
            'fisika' => ['icon' => '⚛', 'name' => 'Physics', 'color' => 'violet'],
            'kimia' => ['icon' => '⚗', 'name' => 'Chemistry', 'color' => 'amber'],
            'sejarah' => ['icon' => '◷', 'name' => 'History', 'color' => 'orange'],
            'informatika' => ['icon' => '</>', 'name' => 'Technology', 'color' => 'cyan'],
        ];
    @endphp

    <style>
        .teacher-page {
            --navy: #102f50;
            --blue: #087bbd;
            --cyan: #38bdf8;
            background: #f4f8fc;
            min-height: 100vh;
            padding: 30px 0 60px;
        }

        .teacher-wrap {
            width: min(1440px, 100%);
            margin: auto;
            padding: 0 24px;
        }

        .teacher-hero {
            position: relative;
            overflow: hidden;
            border-radius: 28px;
            background: linear-gradient(115deg, #102f50 0%, #075985 58%, #0284c7 100%);
            padding: 36px;
            color: white;
            box-shadow: 0 18px 45px rgba(15, 63, 105, .16);
        }

        .teacher-hero::before,
        .teacher-hero::after {
            content: "";
            position: absolute;
            border: 1px solid rgba(255,255,255,.13);
            border-radius: 50%;
            pointer-events: none;
        }

        .teacher-hero::before {
            width: 330px;
            height: 330px;
            right: -80px;
            top: -180px;
        }

        .teacher-hero::after {
            width: 240px;
            height: 240px;
            right: 100px;
            bottom: -180px;
        }

        .teacher-hero-content {
            position: relative;
            z-index: 1;
            max-width: 720px;
        }

        .teacher-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 1px solid rgba(255,255,255,.2);
            background: rgba(255,255,255,.1);
            border-radius: 99px;
            padding: 7px 13px;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        .teacher-stat {
            border: 1px solid #e6edf5;
            background: white;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 6px 22px rgba(15, 41, 69, .035);
            transition: .25s ease;
        }

        .teacher-stat:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 28px rgba(15, 41, 69, .08);
        }

        .teacher-stat-icon {
            width: 44px;
            height: 44px;
            display: grid;
            place-items: center;
            border-radius: 14px;
            font-size: 20px;
            font-weight: 800;
        }

        .teacher-panel {
            border: 1px solid #e7edf4;
            border-radius: 22px;
            background: white;
            padding: 23px;
            box-shadow: 0 5px 22px rgba(15, 41, 69, .035);
        }

        .teacher-class-card {
            display: block;
            overflow: hidden;
            border: 1px solid #e6edf5;
            border-radius: 20px;
            background: white;
            transition: .25s ease;
        }

        .teacher-class-card:hover {
            transform: translateY(-5px);
            border-color: #bae6fd;
            box-shadow: 0 15px 30px rgba(15, 41, 69, .09);
        }

        .teacher-class-cover {
            position: relative;
            min-height: 125px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            overflow: hidden;
            padding: 20px;
        }

        .teacher-class-cover::after {
            content: "";
            position: absolute;
            width: 150px;
            height: 150px;
            right: -35px;
            top: -75px;
            border: 1px solid rgba(255,255,255,.25);
            border-radius: 50%;
            box-shadow: 0 0 0 20px rgba(255,255,255,.05),
                        0 0 0 40px rgba(255,255,255,.04);
        }

        .teacher-class-symbol {
            position: relative;
            z-index: 1;
            display: grid;
            place-items: center;
            width: 70px;
            height: 70px;
            border: 1px solid rgba(255,255,255,.35);
            border-radius: 22px;
            background: rgba(255,255,255,.16);
            color: white;
            font-size: 27px;
            font-weight: 900;
            backdrop-filter: blur(8px);
        }

        .teacher-section-title {
            color: #102f50;
            font-size: 18px;
            font-weight: 800;
            letter-spacing: -.02em;
        }

        .teacher-muted {
            color: #718096;
        }

        .teacher-list-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            border-bottom: 1px solid #edf1f5;
            padding: 13px 0;
        }

        .teacher-list-row:last-child {
            border-bottom: 0;
        }

        @media (max-width: 640px) {
            .teacher-page { padding-top: 20px; }
            .teacher-wrap { padding: 0 15px; }
            .teacher-hero { padding: 25px 21px; border-radius: 22px; }
            .teacher-panel { padding: 17px; }
            .teacher-stat { padding: 16px; }
            .teacher-class-cover { min-height: 105px; }
            .teacher-class-symbol { width: 58px; height: 58px; }
        }
    </style>

    <div class="teacher-page">
        <div class="teacher-wrap space-y-8">

            {{-- HERO --}}
            <section class="teacher-hero">
                <div class="teacher-hero-content">
                    <div class="teacher-eyebrow">
                        <span>✦</span>
                        Welcome to your workspace
                    </div>

                    <h1 class="mt-5 text-3xl font-black leading-tight tracking-tight sm:text-5xl">
                        Halo, {{ Auth::user()->name }}!
                    </h1>

                    <p class="mt-4 max-w-xl text-sm leading-7 text-sky-100 sm:text-base">
                        Selamat datang di ruang kerja guru Methodist Learning Space.
                        Kelola kelas, bagikan materi, pantau perkembangan siswa,
                        dan ciptakan pengalaman belajar yang lebih menyenangkan.
                    </p>

                    <div class="mt-7 flex flex-wrap gap-3">
                        <a href="{{ route('guru.classes.index') }}"
                           class="inline-flex items-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-bold text-sky-800 shadow-lg transition hover:-translate-y-1 hover:bg-sky-50">
                            <span>▤</span>
                            Kelola E-Class
                            <span>→</span>
                        </a>

                        <a href="{{ route('guru.exams.index') }}"
                           class="inline-flex items-center gap-2 rounded-xl border border-white/30 bg-white/10 px-5 py-3 text-sm font-bold text-white transition hover:bg-white/20">
                            <span>✎</span>
                            Kelola E-Exam
                        </a>
                    </div>
                </div>

                <div class="relative z-10 mt-8 flex flex-wrap gap-2 text-xs font-semibold text-sky-100">
                    <span class="rounded-full bg-white/10 px-3 py-2">✦ Teaching</span>
                    <span class="rounded-full bg-white/10 px-3 py-2">✦ Learning</span>
                    <span class="rounded-full bg-white/10 px-3 py-2">✦ Growing Together</span>
                </div>
            </section>

            {{-- STATISTICS --}}
            <section>
                <div class="mb-4 flex items-end justify-between gap-3">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[.16em] text-sky-600">
                            Overview
                        </p>
                        <h2 class="teacher-section-title mt-1">Ringkasan Aktivitas</h2>
                    </div>
                    <span class="hidden text-xs text-slate-400 sm:block">
                        Statistik pembelajaran Anda
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
                    <div class="teacher-stat">
                        <div class="flex items-center justify-between gap-2">
                            <div class="teacher-stat-icon bg-sky-50 text-sky-700">▤</div>
                            <span class="text-xs font-semibold text-sky-600">E-Class</span>
                        </div>
                        <p class="mt-5 text-3xl font-black text-slate-800">
                            {{ $totalClasses }}
                        </p>
                        <p class="mt-1 text-xs font-medium text-slate-500">Kelas yang diampu</p>
                    </div>

                    <div class="teacher-stat">
                        <div class="flex items-center justify-between gap-2">
                            <div class="teacher-stat-icon bg-indigo-50 text-indigo-700">♙</div>
                            <span class="text-xs font-semibold text-indigo-600">Students</span>
                        </div>
                        <p class="mt-5 text-3xl font-black text-slate-800">
                            {{ $totalStudents }}
                        </p>
                        <p class="mt-1 text-xs font-medium text-slate-500">Total siswa</p>
                    </div>

                    <div class="teacher-stat">
                        <div class="flex items-center justify-between gap-2">
                            <div class="teacher-stat-icon bg-amber-50 text-amber-700">✎</div>
                            <span class="text-xs font-semibold text-amber-600">Assignments</span>
                        </div>
                        <p class="mt-5 text-3xl font-black text-slate-800">
                            {{ $totalAssignments }}
                        </p>
                        <p class="mt-1 text-xs font-medium text-slate-500">Tugas dibuat</p>
                    </div>

                    <div class="teacher-stat">
                        <div class="flex items-center justify-between gap-2">
                            <div class="teacher-stat-icon bg-emerald-50 text-emerald-700">✓</div>
                            <span class="text-xs font-semibold text-emerald-600">E-Exam</span>
                        </div>
                        <p class="mt-5 text-3xl font-black text-slate-800">
                            {{ $totalExams }}
                        </p>
                        <p class="mt-1 text-xs font-medium text-slate-500">Ujian dibuat</p>
                    </div>
                </div>
            </section>

            {{-- CLASS LIST --}}
            <section>
                <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[.16em] text-sky-600">
                            My Teaching Space
                        </p>
                        <h2 class="teacher-section-title mt-1">Kelas yang Saya Ampu</h2>
                        <p class="mt-1 text-sm teacher-muted">
                            Pantau kelas dan aktivitas pembelajaran siswa.
                        </p>
                    </div>

                    <a href="{{ route('guru.classes.index') }}"
                       class="rounded-xl bg-sky-50 px-4 py-2.5 text-xs font-bold text-sky-700 transition hover:bg-sky-100">
                        Lihat Semua Kelas →
                    </a>
                </div>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
                    @forelse($overview as $class)
                        @php
                            $subjectLower = strtolower($class->subject_name ?? '');
                            $theme = collect($subjectThemes)->first(
                                fn ($item, $key) => str_contains($subjectLower, $key)
                            ) ?? ['icon' => '▤', 'name' => 'Learning', 'color' => 'sky'];

                            $coverColors = [
                                'blue' => 'background:linear-gradient(135deg,#1d4ed8,#38bdf8)',
                                'rose' => 'background:linear-gradient(135deg,#be123c,#fb7185)',
                                'indigo' => 'background:linear-gradient(135deg,#4338ca,#818cf8)',
                                'emerald' => 'background:linear-gradient(135deg,#047857,#34d399)',
                                'violet' => 'background:linear-gradient(135deg,#6d28d9,#a78bfa)',
                                'amber' => 'background:linear-gradient(135deg,#b45309,#fbbf24)',
                                'orange' => 'background:linear-gradient(135deg,#c2410c,#fb923c)',
                                'cyan' => 'background:linear-gradient(135deg,#0e7490,#22d3ee)',
                                'sky' => 'background:linear-gradient(135deg,#075985,#38bdf8)',
                            ];

                            $classGrades = $gradeSummaries->get($class->cst_id, collect());
                        @endphp

                        <article class="teacher-class-card">
                            <div class="teacher-class-cover"
                                 style="{{ $coverColors[$theme['color']] ?? $coverColors['sky'] }}">
                                <div class="relative z-10 text-white">
                                    <p class="text-[10px] font-bold uppercase tracking-[.2em] text-white/75">
                                        {{ $theme['name'] }}
                                    </p>
                                    <h3 class="mt-2 text-xl font-black">
                                        {{ $class->subject_name }}
                                    </h3>
                                </div>

                                <div class="teacher-class-symbol">
                                    {{ $theme['icon'] }}
                                </div>
                            </div>

                            <div class="p-5">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                                            Kelas
                                        </p>
                                        <h4 class="mt-1 text-lg font-extrabold text-slate-800">
                                            {{ $class->class_name }}
                                        </h4>
                                    </div>
                                    <span class="rounded-full bg-emerald-50 px-3 py-1 text-[10px] font-bold text-emerald-700">
                                        Aktif
                                    </span>
                                </div>

                                <div class="mt-5 grid grid-cols-3 gap-2 border-y border-slate-100 py-4">
                                    <div>
                                        <p class="text-xl font-black text-slate-800">
                                            {{ $class->student_count }}
                                        </p>
                                        <p class="text-[10px] font-semibold text-slate-400">Siswa</p>
                                    </div>
                                    <div>
                                        <p class="text-xl font-black text-slate-800">
                                            {{ $class->assignment_count }}
                                        </p>
                                        <p class="text-[10px] font-semibold text-slate-400">Tugas</p>
                                    </div>
                                    <div>
                                        <p class="text-xl font-black text-slate-800">
                                            {{ $class->exam_count }}
                                        </p>
                                        <p class="text-[10px] font-semibold text-slate-400">Ujian</p>
                                    </div>
                                </div>

                                <div class="mt-4 flex items-center justify-between gap-2">
                                    <span class="text-xs text-slate-500">
                                        {{ $class->published_exam_count }} ujian dipublikasikan
                                    </span>

                                    <a href="{{ route('guru.classes.show', $class->cst_id) }}"
                                       class="inline-flex items-center gap-2 rounded-xl bg-sky-600 px-4 py-2.5 text-xs font-bold text-white transition hover:bg-sky-700">
                                        Buka Kelas <span>→</span>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="col-span-full rounded-2xl border-2 border-dashed border-slate-200 bg-white px-6 py-14 text-center">
                            <div class="mx-auto grid h-16 w-16 place-items-center rounded-2xl bg-sky-50 text-3xl text-sky-600">
                                ▤
                            </div>
                            <h3 class="mt-4 text-lg font-bold text-slate-800">
                                Belum Ada Kelas
                            </h3>
                            <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                                Kelas yang Anda ampu akan ditampilkan di sini.
                                Silakan periksa pengaturan kelas Anda.
                            </p>
                            <a href="{{ route('guru.classes.index') }}"
                               class="mt-5 inline-flex rounded-xl bg-sky-600 px-5 py-3 text-sm font-bold text-white hover:bg-sky-700">
                                Kelola Kelas →
                            </a>
                        </div>
                    @endforelse
                </div>
            </section>

            {{-- ASSIGNMENTS AND SCHEDULE --}}
            <section class="grid grid-cols-1 gap-5 xl:grid-cols-2">

                <div class="teacher-panel">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[.15em] text-sky-600">
                                Assignment Monitor
                            </p>
                            <h3 class="teacher-section-title mt-1">Status Penilaian Tugas</h3>
                        </div>
                        <div class="grid h-11 w-11 place-items-center rounded-xl bg-amber-50 text-xl text-amber-600">
                            ✎
                        </div>
                    </div>

                    <div class="mt-5">
                        @forelse($assignmentStatuses as $assignment)
                            <div class="teacher-list-row">
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-bold text-slate-800">
                                        {{ $assignment->title }}
                                    </p>
                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ $assignment->class_name }}
                                    </p>
                                </div>
                                <div class="shrink-0 text-right">
                                    <p class="text-sm font-extrabold text-slate-800">
                                        {{ $assignment->graded_count }}/{{ $assignment->submitted_count }}
                                    </p>
                                    <p class="text-[10px] font-semibold text-slate-400">Sudah dinilai</p>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-xl bg-slate-50 p-5 text-center">
                                <p class="text-sm font-semibold text-slate-600">
                                    Belum ada tugas yang dibuat.
                                </p>
                                <p class="mt-1 text-xs text-slate-400">
                                    Tugas yang dibuat akan muncul di bagian ini.
                                </p>
                            </div>
                        @endforelse
                    </div>

                    @if($totalUngraded > 0)
                        <div class="mt-4 flex items-center gap-3 rounded-xl border border-amber-100 bg-amber-50 p-4">
                            <span class="text-xl">⏳</span>
                            <div>
                                <p class="text-sm font-bold text-amber-800">
                                    {{ $totalUngraded }} esai menunggu koreksi
                                </p>
                                <p class="text-xs text-amber-700">
                                    Periksa hasil pekerjaan siswa Anda.
                                </p>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="teacher-panel">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[.15em] text-sky-600">
                                Teaching Schedule
                            </p>
                            <h3 class="teacher-section-title mt-1">Jadwal Mengajar</h3>
                        </div>
                        <div class="grid h-11 w-11 place-items-center rounded-xl bg-indigo-50 text-xl text-indigo-600">
                            ◷
                        </div>
                    </div>

                    <div class="mt-5">
                        @forelse($schedule as $slot)
                            <div class="teacher-list-row">
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-slate-800">
                                        {{ ucfirst($slot->day) }}
                                    </p>
                                    <p class="mt-1 truncate text-xs text-slate-500">
                                        {{ $slot->class_name }} · {{ $slot->subject_name }}
                                    </p>
                                </div>
                                <span class="shrink-0 rounded-lg bg-sky-50 px-3 py-2 text-xs font-bold text-sky-700">
                                    {{ substr($slot->start_time, 0, 5) }}–{{ substr($slot->end_time, 0, 5) }}
                                </span>
                            </div>
                        @empty
                            <div class="rounded-xl bg-slate-50 p-5 text-center">
                                <p class="text-sm font-semibold text-slate-600">
                                    Belum ada jadwal mengajar.
                                </p>
                                <p class="mt-1 text-xs text-slate-400">
                                    Jadwal Anda akan muncul di bagian ini.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </section>

            {{-- STUDENTS AND GRADES --}}
            <section class="grid grid-cols-1 gap-5 xl:grid-cols-2">

                <div class="teacher-panel">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[.15em] text-sky-600">
                                Student Directory
                            </p>
                            <h3 class="teacher-section-title mt-1">Daftar Siswa</h3>
                        </div>
                        <div class="grid h-11 w-11 place-items-center rounded-xl bg-violet-50 text-xl text-violet-600">
                            ♙
                        </div>
                    </div>

                    <div class="mt-5 space-y-4">
                        @forelse($roster as $cstId => $students)
                            <div>
                                <p class="mb-2 text-xs font-extrabold uppercase tracking-wide text-slate-500">
                                    {{ $students->first()->class_name }}
                                    · {{ $students->first()->subject_name }}
                                </p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($students as $student)
                                        <span class="rounded-lg border border-slate-100 bg-slate-50 px-3 py-2 text-xs font-semibold text-slate-700">
                                            {{ $student->student_name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <p class="rounded-xl bg-slate-50 p-5 text-center text-sm text-slate-500">
                                Belum ada siswa yang terdaftar.
                            </p>
                        @endforelse
                    </div>
                </div>

                <div class="teacher-panel">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[.15em] text-sky-600">
                                Latest Assessment
                            </p>
                            <h3 class="teacher-section-title mt-1">Nilai Terbaru</h3>
                        </div>
                        <div class="grid h-11 w-11 place-items-center rounded-xl bg-emerald-50 text-xl text-emerald-600">
                            ★
                        </div>
                    </div>

                    <div class="mt-5">
                        @forelse($gradebook->take(10) as $grade)
                            <div class="teacher-list-row">
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-bold text-slate-800">
                                        {{ $grade->student_name }}
                                    </p>
                                    <p class="mt-1 truncate text-xs text-slate-500">
                                        {{ $grade->source_title }}
                                    </p>
                                </div>
                                <div class="shrink-0 text-right">
                                    <p class="text-base font-black text-sky-700">
                                        {{ $grade->score }}
                                    </p>
                                    <p class="text-[10px] font-bold text-slate-400">
                                        {{ $grade->predicate }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <p class="rounded-xl bg-slate-50 p-5 text-center text-sm text-slate-500">
                                Belum ada nilai yang tersedia.
                            </p>
                        @endforelse
                    </div>
                </div>
            </section>

            {{-- FOOTER --}}
            <footer class="border-t border-slate-200 pt-6 text-center">
                <p class="text-xs font-medium text-slate-400">
                    Methodist Learning Space · Empowering Learning, Inspiring Future.
                </p>
            </footer>

        </div>
    </div>
</x-app-layout>
