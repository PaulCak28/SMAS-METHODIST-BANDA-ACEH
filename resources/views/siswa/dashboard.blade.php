<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="font-serif text-2xl font-bold tracking-tight text-slate-800">
                    {{ __('Dashboard Siswa') }}
                </h2>
                <p class="text-xs text-slate-500">
                    Methodist Learning Space &bull; SMAS Methodist Banda Aceh
                </p>
            </div>
            <div class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3.5 py-1.5 text-xs font-semibold text-slate-600 shadow-sm">
                <svg class="h-4 w-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <rect x="3" y="5" width="18" height="16" rx="2" stroke-width="2"/>
                    <path stroke-linecap="round" stroke-width="2" d="M16 3v4M8 3v4M3 10h18"/>
                </svg>
                <span>{{ now()->locale('id')->translatedFormat('l, d F Y') }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- ALERT NOTIFIKASI FEEDBACK --}}
            @if (session('success'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 shadow-sm flex items-start gap-3">
                    <svg class="h-5 w-5 text-emerald-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="font-bold">Berhasil!</p>
                        <p class="mt-0.5 text-xs text-emerald-700">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800 shadow-sm flex items-start gap-3">
                    <svg class="h-5 w-5 text-rose-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="font-bold">Perhatian</p>
                        <p class="mt-0.5 text-xs text-rose-700">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            {{-- HERO BANNER IDENTITAS SISWA (MODERN GLASSMORPHISM & GRADIENT) --}}
            <section class="relative isolate overflow-hidden rounded-3xl bg-gradient-to-br from-[#0e172a] via-[#101C35] to-[#1e3a8a] p-6 text-white shadow-2xl sm:p-10">
                {{-- Ambient Background Glow --}}
                <div class="absolute -right-20 -top-20 -z-10 h-80 w-80 rounded-full bg-blue-500/20 blur-3xl"></div>
                <div class="absolute -bottom-20 left-1/3 -z-10 h-80 w-80 rounded-full bg-sky-400/15 blur-3xl"></div>

                <div class="grid items-center gap-8 lg:grid-cols-12">
                    
                    {{-- Sapaan & Detail Profil Siswa --}}
                    <div class="lg:col-span-8">
                        <div class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3.5 py-1 text-xs font-semibold text-white/90 backdrop-blur-md">
                            <span class="flex h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span>Siswa Aktif SMAS Methodist</span>
                        </div>

                        <h1 class="mt-4 font-serif text-3xl font-bold tracking-tight text-white sm:text-4xl lg:text-5xl">
                            Halo, <span class="text-sky-300">{{ $user->name }}</span>!
                        </h1>

                        <p class="mt-3 max-w-2xl text-sm leading-relaxed text-slate-200 sm:text-base">
                            Selamat datang di portal akademik digitalmu. Pantau materi pelajaran, kerjakan tugas tepat waktu, dan ikuti ujian dengan jujur dan penuh semangat.
                        </p>

                        {{-- Metadata Siswa (NIS, Kelas, Wali Kelas) --}}
                        <div class="mt-6 flex flex-wrap items-center gap-2.5 sm:gap-3 text-xs">
                            <div class="inline-flex items-center gap-2 rounded-xl border border-white/15 bg-white/10 px-3.5 py-2 backdrop-blur-md">
                                <span class="text-slate-400">NIS:</span>
                                <span class="font-mono font-bold text-white">{{ $student->nis ?? '-' }}</span>
                            </div>

                            <div class="inline-flex items-center gap-2 rounded-xl border border-white/15 bg-white/10 px-3.5 py-2 backdrop-blur-md">
                                <span class="text-slate-400">NISN:</span>
                                <span class="font-mono font-bold text-white">{{ $student->nisn ?? '-' }}</span>
                            </div>

                            <div class="inline-flex items-center gap-2 rounded-xl border border-sky-400/40 bg-sky-500/20 px-3.5 py-2 backdrop-blur-md">
                                <span class="text-sky-200">Kelas:</span>
                                <span class="font-bold text-white">{{ $student->schoolClass->name ?? 'Belum Ditentukan' }}</span>
                            </div>

                            <div class="inline-flex items-center gap-2 rounded-xl border border-white/15 bg-white/10 px-3.5 py-2 backdrop-blur-md">
                                <span class="text-slate-400">Wali Kelas:</span>
                                <span class="font-semibold text-white">
                                    {{ $student->schoolClass?->homeroomTeacher?->user?->name ?? 'Belum Ditugaskan' }}
                                </span>
                            </div>
                        </div>

                        {{-- Tombol Aksi Cepat --}}
                        <div class="mt-8 flex flex-wrap items-center gap-3">
                            <a href="{{ route('siswa.classes.index') }}"
                               class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-5 py-3 text-xs sm:text-sm font-bold text-[#101C35] shadow-lg shadow-black/20 transition hover:bg-slate-100 hover:shadow-xl hover:-translate-y-0.5">
                                <svg class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.25v13m0-13C10.83 5.48 9.25 5 7.5 5S4.17 5.48 3 6.25v13C4.17 18.48 5.75 18 7.5 18s3.33.48 4.5 1.25m0-13C13.17 5.48 14.75 5 16.5 5c1.75 0 3.33.48 4.5 1.25v13C19.83 18.48 18.25 18 16.5 18c-1.75 0-3.33.48-4.5 1.25"/>
                                </svg>
                                <span>Buka E-Class Saya</span>
                            </a>

                            <a href="{{ route('siswa.exams.index') }}"
                               class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/20 bg-white/10 px-5 py-3 text-xs sm:text-sm font-semibold text-white backdrop-blur-md transition hover:bg-white/20 hover:-translate-y-0.5">
                                <svg class="h-4 w-4 text-sky-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span>Jadwal Ujian (E-Exam)</span>
                            </a>
                        </div>
                    </div>

                    {{-- Kartu Ringkasan Samping (Visual Card) --}}
                    <div class="lg:col-span-4">
                        <div class="rounded-2xl border border-white/20 bg-white/10 p-6 backdrop-blur-xl shadow-xl">
                            <div class="flex items-center gap-4">
                                <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-tr from-blue-600 to-sky-400 text-2xl font-bold text-white shadow-lg shadow-sky-500/30">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <h3 class="truncate text-base font-bold text-white">{{ $user->name }}</h3>
                                    <p class="truncate text-xs text-sky-200">{{ $user->email }}</p>
                                    <span class="mt-1 inline-flex items-center rounded-full bg-emerald-400/20 px-2 py-0.5 text-[10px] font-bold uppercase text-emerald-300">
                                        {{ $student->gender === 'L' ? 'Laki-Laki' : ($student->gender === 'P' ? 'Perempuan' : 'Siswa') }}
                                    </span>
                                </div>
                            </div>

                            <div class="mt-5 border-t border-white/10 pt-4 space-y-2.5 text-xs text-slate-200">
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-400">Tahun Ajaran:</span>
                                    <span class="font-semibold">{{ $student->schoolClass->academic_year ?? '2024/2025' }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-400">Status Akun:</span>
                                    <span class="font-semibold text-emerald-300 capitalize">{{ $student->status ?? 'Aktif' }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-400">Tahun Masuk:</span>
                                    <span class="font-semibold">{{ $student->entry_year ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </section>

            {{-- 4 KARTU STATISTIK CEPAT (QUICK STATS GRID) --}}
            <section class="grid grid-cols-2 gap-4 sm:grid-cols-4 lg:gap-6">
                
                {{-- Stat 1: Total Kelas --}}
                <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:shadow-md">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.25v13m0-13C10.83 5.48 9.25 5 7.5 5S4.17 5.48 3 6.25v13C4.17 18.48 5.75 18 7.5 18s3.33.48 4.5 1.25m0-13C13.17 5.48 14.75 5 16.5 5c1.75 0 3.33.48 4.5 1.25v13C19.83 18.48 18.25 18 16.5 18c-1.75 0-3.33.48-4.5 1.25"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-serif text-2xl font-bold text-slate-800">{{ $totalJoined }}</p>
                        <p class="text-xs font-medium text-slate-500">Kelas Diikuti</p>
                    </div>
                </div>

                {{-- Stat 2: Tugas Menunggu --}}
                <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:shadow-md">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-serif text-2xl font-bold text-slate-800">{{ $pendingAssignments->count() }}</p>
                        <p class="text-xs font-medium text-slate-500">Tugas Pending</p>
                    </div>
                </div>

                {{-- Stat 3: Ujian Tersedia --}}
                <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:shadow-md">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-purple-50 text-purple-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-serif text-2xl font-bold text-slate-800">{{ $upcomingExams->count() }}</p>
                        <p class="text-xs font-medium text-slate-500">Ujian CBT</p>
                    </div>
                </div>

                {{-- Stat 4: Tugas Terkumpul --}}
                <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:shadow-md">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-serif text-2xl font-bold text-slate-800">{{ $submittedCount }}</p>
                        <p class="text-xs font-medium text-slate-500">Tugas Diserahkan</p>
                    </div>
                </div>

            </section>

            {{-- KONTEN UTAMA: 2 KOLOM (KOLOM KIRI 8 COLS, KOLOM KANAN 4 COLS) --}}
            <div class="grid gap-8 lg:grid-cols-12">
                
                {{-- KOLOM KIRI (8 COLS): KELAS SAYA & TUGAS PENDING --}}
                <div class="space-y-8 lg:col-span-8">
                    
                    {{-- Section: Mata Pelajaran Aktif (E-Class) --}}
                    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-7">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                            <div>
                                <h3 class="font-serif text-xl font-bold text-slate-800">
                                    Kelas Mata Pelajaran
                                </h3>
                                <p class="text-xs text-slate-500">
                                    Rombongan belajar yang sedang kamu ikuti di semester ini.
                                </p>
                            </div>
                            <a href="{{ route('siswa.classes.index') }}" class="inline-flex items-center gap-1 text-xs font-bold text-blue-600 hover:text-blue-800 transition">
                                <span>Lihat Semua</span>
                                <span>&rarr;</span>
                            </a>
                        </div>

                        @if ($joinedClasses->isNotEmpty())
                            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                                @foreach ($joinedClasses as $cst)
                                    <article class="group flex flex-col justify-between rounded-2xl border border-slate-200 bg-slate-50/50 p-5 transition hover:-translate-y-1 hover:border-blue-400 hover:bg-white hover:shadow-lg">
                                        <div>
                                            <div class="flex items-start justify-between gap-2">
                                                <span class="rounded-lg bg-blue-100 px-2.5 py-1 text-[11px] font-bold text-blue-800">
                                                    {{ $cst->subject_code }}
                                                </span>
                                                <span class="text-[11px] font-semibold text-slate-400">
                                                    {{ $cst->schoolClass->name ?? '' }}
                                                </span>
                                            </div>

                                            <h4 class="mt-3 font-serif text-lg font-bold text-slate-800 group-hover:text-blue-600 transition">
                                                {{ $cst->subject->name ?? $cst->subject_code }}
                                            </h4>

                                            <p class="mt-1 text-xs text-slate-500 flex items-center gap-1.5">
                                                <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                                <span>{{ $cst->teacher?->user?->name ?? 'Guru Pengampu' }}</span>
                                            </p>
                                        </div>

                                        <div class="mt-5 border-t border-slate-200/80 pt-3">
                                            <div class="flex items-center justify-between text-xs text-slate-500">
                                                <span>{{ $cst->materials_count }} Materi</span>
                                                <span>&bull;</span>
                                                <span>{{ $cst->assignments_count }} Tugas</span>
                                                <span>&bull;</span>
                                                <span>{{ $cst->exams_count }} Ujian</span>
                                            </div>

                                            <a href="{{ route('siswa.classes.show', $cst->cst_id) }}"
                                               class="mt-3 inline-flex w-full items-center justify-center gap-1.5 rounded-xl bg-white border border-slate-200 py-2 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-blue-600 hover:text-white hover:border-blue-600">
                                                <span>Masuk Kelas</span>
                                                <span>&rarr;</span>
                                            </a>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        @else
                            {{-- Empty State: Belum Ada Kelas --}}
                            <div class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-slate-50/70 p-8 text-center">
                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-100 text-blue-600">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.25v13m0-13C10.83 5.48 9.25 5 7.5 5S4.17 5.48 3 6.25v13C4.17 18.48 5.75 18 7.5 18s3.33.48 4.5 1.25m0-13C13.17 5.48 14.75 5 16.5 5c1.75 0 3.33.48 4.5 1.25v13C19.83 18.48 18.25 18 16.5 18c-1.75 0-3.33.48-4.5 1.25"/>
                                    </svg>
                                </div>
                                <h4 class="mt-3 font-serif text-base font-bold text-slate-800">Belum Ada Kelas yang Diikuti</h4>
                                <p class="mx-auto mt-1 max-w-sm text-xs leading-relaxed text-slate-500">
                                    Dapatkan kode gabung kelas dari guru mata pelajaranmu, lalu masukkan pada formulir di sebelah kanan untuk mulai belajar.
                                </p>
                            </div>
                        @endif
                    </section>

                    {{-- Section: Tugas Mendekati Tenggat Waktu --}}
                    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-7">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                            <div>
                                <h3 class="font-serif text-xl font-bold text-slate-800">
                                    Tugas Perlu Dikerjakan
                                </h3>
                                <p class="text-xs text-slate-500">
                                    Daftar penugasan aktif yang belum kamu kumpulkan.
                                </p>
                            </div>
                            <span class="rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-bold text-amber-800">
                                {{ $pendingAssignments->count() }} Menunggu
                            </span>
                        </div>

                        @if ($pendingAssignments->isNotEmpty())
                            <div class="mt-4 divide-y divide-slate-100">
                                @foreach ($pendingAssignments as $assignment)
                                    @php
                                        $isUrgent = $assignment->due_date && $assignment->due_date->isFuture() && $assignment->due_date->diffInHours(now()) <= 24;
                                        $isOverdue = $assignment->due_date && $assignment->due_date->isPast();
                                    @endphp
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 py-4">
                                        <div class="space-y-1">
                                            <div class="flex items-center gap-2">
                                                <span class="rounded bg-slate-100 px-2 py-0.5 text-[10px] font-bold text-slate-700">
                                                    {{ $assignment->classSubjectTeacher?->subject?->name ?? 'Mata Pelajaran' }}
                                                </span>
                                                @if ($isOverdue)
                                                    <span class="rounded bg-rose-100 px-2 py-0.5 text-[10px] font-bold text-rose-700">Lewat Tenggat</span>
                                                @elseif ($isUrgent)
                                                    <span class="rounded bg-amber-100 px-2 py-0.5 text-[10px] font-bold text-amber-800 animate-pulse">Segera Berakhir</span>
                                                @endif
                                            </div>
                                            <h4 class="font-medium text-sm text-slate-800">
                                                {{ $assignment->title }}
                                            </h4>
                                            <p class="text-xs text-slate-400">
                                                Batas pengumpulan: <span class="font-semibold text-slate-600">{{ $assignment->due_date ? $assignment->due_date->translatedFormat('d M Y, H:i') : 'Tanpa batas' }}</span>
                                            </p>
                                        </div>

                                        <a href="{{ route('siswa.classes.show', $assignment->cst_id) }}"
                                           class="inline-flex shrink-0 items-center justify-center gap-1.5 rounded-xl bg-blue-50 px-4 py-2 text-xs font-bold text-blue-700 hover:bg-blue-600 hover:text-white transition">
                                            <span>Kumpulkan Tugas</span>
                                            <span>&rarr;</span>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            {{-- Semua Tugas Selesai --}}
                            <div class="mt-6 rounded-2xl bg-emerald-50/50 border border-emerald-100 p-6 text-center">
                                <span class="text-3xl">🎉</span>
                                <h4 class="mt-2 font-serif text-sm font-bold text-emerald-900">Hebat! Semua Tugas Sudah Kamu Kumpulkan</h4>
                                <p class="mt-1 text-xs text-emerald-700">Tidak ada penugasan tertunda saat ini. Manfaatkan waktu untuk mengulang materi di E-Class.</p>
                            </div>
                        @endif
                    </section>

                </div>

                {{-- KOLOM KANAN (4 COLS): GABUNG KELAS, JADWAL CBT, & WARTA SEKOLAH --}}
                <div class="space-y-8 lg:col-span-4">
                    
                    {{-- Widget 1: Formulir Cepat Gabung Kelas --}}
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-serif text-base font-bold text-slate-800">Gabung Kelas Baru</h3>
                                <p class="text-[11px] text-slate-500">Masukkan kode kelas dari gurumu.</p>
                            </div>
                        </div>

                        <form action="{{ route('siswa.classes.join') }}" method="POST" class="mt-5 space-y-3">
                            @csrf
                            <div>
                                <label for="join_code" class="block text-xs font-semibold text-slate-700">
                                    Kode Kelas (8 Karakter)
                                </label>
                                <input
                                    id="join_code"
                                    name="join_code"
                                    type="text"
                                    maxlength="8"
                                    placeholder="Contoh: 10AMAT1"
                                    required
                                    style="text-transform: uppercase;"
                                    class="mt-1.5 w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-center font-mono text-sm tracking-wider uppercase text-slate-800 placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                >
                            </div>

                            <button
                                type="submit"
                                class="w-full rounded-xl bg-blue-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-blue-700 active:scale-[0.99]"
                            >
                                Gabung ke Rombel
                            </button>
                        </form>
                    </div>

                    {{-- Widget 2: Ujian Daring (E-Exam Terdekat) --}}
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <h3 class="font-serif text-base font-bold text-slate-800">Jadwal Ujian (CBT)</h3>
                            <a href="{{ route('siswa.exams.index') }}" class="text-[11px] font-bold text-blue-600 hover:text-blue-800">
                                Semua &rarr;
                            </a>
                        </div>

                        @if ($upcomingExams->isNotEmpty())
                            <div class="mt-4 space-y-3">
                                @foreach ($upcomingExams as $exam)
                                    @php
                                        $isOpen = $exam->isOpen();
                                    @endphp
                                    <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4 transition hover:bg-white hover:border-slate-200 hover:shadow-sm">
                                        <div class="flex items-center justify-between gap-2">
                                            <span class="rounded bg-blue-100 px-2 py-0.5 text-[10px] font-bold text-blue-800">
                                                {{ $exam->classSubjectTeacher?->subject?->name ?? 'Ujian' }}
                                            </span>
                                            @if ($isOpen)
                                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-bold text-emerald-700">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                                    Sedang Berlangsung
                                                </span>
                                            @else
                                                <span class="rounded bg-slate-200 px-2 py-0.5 text-[10px] font-semibold text-slate-600">
                                                    Terjadwal
                                                </span>
                                            @endif
                                        </div>

                                        <h4 class="mt-2 text-sm font-bold text-slate-800">{{ $exam->title }}</h4>
                                        <p class="mt-1 text-xs text-slate-500">
                                            Durasi: {{ $exam->duration_minutes }} menit &bull; Mulai: {{ $exam->start_time->translatedFormat('d M, H:i') }}
                                        </p>

                                        <a href="{{ route('siswa.exams.show', $exam->exam_id) }}"
                                           class="mt-3 inline-flex w-full items-center justify-center gap-1 rounded-lg {{ $isOpen ? 'bg-emerald-600 text-white hover:bg-emerald-700' : 'bg-slate-200 text-slate-700 hover:bg-slate-300' }} py-1.5 text-xs font-bold transition">
                                            <span>{{ $isOpen ? 'Mulai Kerjakan Ujian' : 'Lihat Informasi' }}</span>
                                            <span>&rarr;</span>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="mt-4 p-5 text-center text-xs text-slate-400">
                                <svg class="mx-auto h-8 w-8 text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Belum ada ujian yang dijadwalkan dalam waktu dekat.
                            </div>
                        @endif
                    </div>

                    {{-- Widget 3: Warta & Pengumuman Sekolah --}}
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <h3 class="font-serif text-base font-bold text-slate-800">Warta Sekolah</h3>
                            <a href="{{ route('announcements.index') }}" class="text-[11px] font-bold text-blue-600 hover:text-blue-800">
                                Arsip &rarr;
                            </a>
                        </div>

                        @if ($announcements->isNotEmpty())
                            <div class="mt-4 space-y-3">
                                @foreach ($announcements as $info)
                                    <article class="border-b border-slate-100 pb-3 last:border-b-0 last:pb-0">
                                        <div class="flex items-center justify-between text-[10px] text-slate-400">
                                            <span class="font-semibold text-blue-600 capitalize">{{ $info->category }}</span>
                                            <time>{{ optional($info->published_at)->translatedFormat('d M Y') }}</time>
                                        </div>
                                        <h4 class="mt-1 text-xs font-bold text-slate-800 hover:text-blue-600">
                                            <a href="{{ route('announcements.show', $info->slug) }}">
                                                {{ $info->title }}
                                            </a>
                                        </h4>
                                    </article>
                                @endforeach
                            </div>
                        @else
                            <div class="mt-4 text-center text-xs text-slate-400">
                                Belum ada warta pengumuman terbaru.
                            </div>
                        @endif
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
