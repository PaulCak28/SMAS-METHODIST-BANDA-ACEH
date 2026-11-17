<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-600 text-white shadow-lg shadow-blue-200">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                        d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z" />
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-extrabold tracking-tight text-slate-800">E-Class Siswa</h2>
                <p class="text-xs font-medium text-slate-500">SMAS Methodist Banda Aceh · Learning Portal</p>
            </div>
        </div>
    </x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap');

        .student-dashboard {
            font-family: 'DM Sans', sans-serif;
        }

        /* Hero Pattern */
        .student-dashboard .hero-pattern {
            background-color: #102d5e !important;
            background-image:
                linear-gradient(
                    135deg,
                    #0b1f45 0%,
                    #123b78 50%,
                    #1557a5 100%
                ) !important;
        }

        /* Class Card */
        .student-dashboard .class-card {
            transition:
                transform .25s ease,
                box-shadow .25s ease,
                border-color .25s ease;
            border-color: #e2e8f0;
        }

        .student-dashboard .class-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 22px 45px -22px rgba(30, 64, 175, .28);
            border-color: #93c5fd;
        }

        .student-dashboard .class-card:hover .class-arrow {
            transform: translateX(4px);
        }

        /* Arrow Animation */
        .student-dashboard .class-arrow {
            transition: transform .2s ease;
            color: #2563eb;
        }

        /* Soft Shadow */
        .student-dashboard .soft-shadow {
            box-shadow: 0 8px 30px -18px rgba(30, 64, 175, .18);
        }

        /* Blue Accent */
        .student-dashboard .blue-accent {
            color: #2563eb;
        }

        .student-dashboard .blue-gradient {
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 55%, #0ea5e9 100%);
        }

        /* Reduced Motion */
        @media (prefers-reduced-motion: reduce) {
            .student-dashboard *,
            .student-dashboard *::before,
            .student-dashboard *::after {
                transition-duration: .01ms !important;
                animation-duration: .01ms !important;
            }
        }
    </style>

    <div class="student-dashboard min-h-screen bg-[#f5f8fd] pb-14">
        <div class="mx-auto max-w-[1440px] space-y-8 px-4 py-6 sm:px-6 lg:px-9 lg:py-9">

            {{-- HERO SECTION --}}
            <section class="hero-pattern relative isolate overflow-hidden rounded-[2rem] !bg-none !bg-[#102d5e] px-6 py-9 text-white shadow-2xl shadow-blue-950/40 sm:px-9 sm:py-11 lg:px-12 lg:py-12">                <div class="pointer-events-none absolute -right-20 -top-28 h-80 w-80 rounded-full border-[42px] border-white/[0.07]"></div>
                <div class="pointer-events-none absolute -bottom-36 right-[25%] h-80 w-80 rounded-full bg-cyan-300/10 blur-3xl"></div>
                <div class="pointer-events-none absolute right-[8%] top-[35%] h-32 w-32 rounded-full border border-white/10"></div>
                <div class="pointer-events-none absolute bottom-8 right-[35%] h-3 w-3 rounded-full bg-cyan-200/70"></div>

                <div class="relative grid items-center gap-10 lg:grid-cols-[1fr_340px]">
                    <div class="max-w-2xl">
                        <div class="inline-flex items-center gap-2.5 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-[10px] font-bold uppercase tracking-[.18em] text-blue-50 backdrop-blur-md sm:text-xs">
                            <span class="relative flex h-2 w-2">
                                <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-300 opacity-60"></span>
                                <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-300"></span>
                            </span>
                            Student Learning Environment
                        </div>

                        <p class="mt-7 text-sm font-semibold text-blue-100">
                            SELAMAT DATANG DI E-CLASS
                        </p>

                        <h1 class="mt-2 text-3xl font-extrabold leading-[1.18] tracking-tight sm:text-4xl lg:text-[3.15rem]">
                            Belajar hari ini,<br class="hidden sm:block">
                            <span class="text-cyan-200">bangun masa depan.</span>
                        </h1>

                       <p class="mt-5 max-w-xl text-sm leading-7 text-white/90 sm:text-base">
                            Selamat datang di ruang belajar digital SMAS Methodist Banda Aceh.
                            Akses materi, kerjakan tugas, dan ikuti ujian dalam satu tempat.
                            Terus berkembang dan berikan yang terbaik!
                        </p>

                        <div class="mt-8 flex flex-wrap items-center gap-3">
                            <a href="#daftar-kelas"
                                class="inline-flex items-center gap-2.5 rounded-xl bg-white px-5 py-3.5 text-sm font-extrabold text-blue-800 shadow-xl shadow-blue-950/15 transition hover:-translate-y-1 hover:bg-blue-50 focus:outline-none focus:ring-4 focus:ring-white/30">
                                Jelajahi kelas
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-6-6 6 6-6 6" />
                                </svg>
                            </a>

                            <div class="inline-flex items-center gap-2 rounded-xl border border-white/20 bg-white/10 px-4 py-3.5 text-sm font-semibold text-white backdrop-blur-sm">
                                <svg class="h-4 w-4 text-cyan-200" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 2m6-2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                Your learning journey starts here
                            </div>
                        </div>
                    </div>

                    {{-- HERO ILLUSTRATION --}}
                    <div class="relative hidden h-64 items-center justify-center lg:flex">
                        <div class="absolute h-64 w-64 rounded-full border border-white/10"></div>
                        <div class="absolute h-52 w-52 rounded-full border border-white/10"></div>

                        <div class="relative flex h-48 w-48 rotate-3 items-center justify-center rounded-[2.5rem] border border-white/20 bg-white/10 shadow-2xl backdrop-blur-xl">
                            <div class="absolute inset-3 rounded-[2rem] border border-white/15"></div>

                            <svg class="h-28 w-28 text-white drop-shadow-xl" viewBox="0 0 100 100" fill="none">
                                <path d="M10 29 50 13l40 16v43L50 88 10 72V29Z" stroke="currentColor" stroke-width="3" stroke-linejoin="round" />
                                <path d="M10 29 50 46l40-17M50 46v42" stroke="currentColor" stroke-width="3" stroke-linejoin="round" />
                                <path d="m29 21 40 17M30 57h13m-13 9h27" stroke="currentColor" stroke-width="3" stroke-linecap="round" />
                            </svg>

                            <div class="absolute -bottom-4 -left-7 rounded-xl bg-white px-4 py-2.5 text-xs font-extrabold tracking-wide text-blue-800 shadow-xl">
                                KEEP LEARNING ✦
                            </div>
                            <div class="absolute -right-7 -top-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-cyan-300 text-xl text-blue-900 shadow-lg">
                                ✦
                            </div>
                        </div>

                        <div class="absolute right-0 top-5 rounded-2xl border border-white/15 bg-white/10 px-4 py-3 backdrop-blur-md">
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-blue-100">Your classroom</p>
                            <p class="mt-1 text-sm font-extrabold">Learn & Grow</p>
                        </div>

                        <div class="absolute bottom-3 left-0 rounded-2xl border border-white/15 bg-white/10 px-4 py-3 backdrop-blur-md">
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-blue-100">Education platform</p>
                            <p class="mt-1 text-sm font-extrabold">Smart Learning</p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ALERTS --}}
            @if(session('success'))
                <div role="status" class="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-800 soft-shadow">
                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-emerald-100 font-bold">✓</span>
                    <span class="pt-1 font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div role="alert" class="flex items-start gap-3 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-800 soft-shadow">
                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-rose-100 font-bold">!</span>
                    <span class="pt-1 font-medium">{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div role="alert" class="flex items-start gap-3 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-800 soft-shadow">
                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-rose-100 font-bold">!</span>
                    <span class="pt-1 font-medium">{{ $errors->first() }}</span>
                </div>
            @endif

            {{-- OVERVIEW --}}
            <section class="grid gap-4 md:grid-cols-3">
                {{-- Total classes --}}
                <div class="group rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-lg hover:shadow-blue-100/60 sm:p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-blue-700">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v16H6.5A2.5 2.5 0 0 0 4 21V5.5ZM4 17a2.5 2.5 0 0 1 2.5-2.5H20" />
                            </svg>
                        </div>
                        <span class="rounded-full bg-blue-50 px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-blue-700">My Classes</span>
                    </div>
                    <p class="mt-5 text-sm font-semibold text-slate-500">Kelas yang diikuti</p>
                    <div class="mt-1 flex items-end gap-2">
                        <h3 class="text-3xl font-extrabold tracking-tight text-slate-800">{{ $joined->count() }}</h3>
                        <span class="pb-1 text-sm font-medium text-slate-400">kelas</span>
                    </div>
                    <div class="mt-4 h-1.5 overflow-hidden rounded-full bg-slate-100">
                        <div class="h-full w-full rounded-full bg-gradient-to-r from-blue-600 to-sky-400"></div>
                    </div>
                </div>

                {{-- School class --}}
                <div class="group rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-lg hover:shadow-blue-100/60 sm:p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-700">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-5h6v5M9 9h.01M15 9h.01M9 12h.01M15 12h.01" />
                            </svg>
                        </div>
                        <span class="rounded-full bg-indigo-50 px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-indigo-700">Academic Info</span>
                    </div>
                    <p class="mt-5 text-sm font-semibold text-slate-500">Rombongan belajar</p>
                    <h3 class="mt-1 truncate text-xl font-extrabold tracking-tight text-slate-800">
                        {{ auth()->user()->student->schoolClass->name ?? 'Belum ditentukan' }}
                    </h3>
                    <p class="mt-2 text-xs text-slate-400">Informasi kelas akademikmu</p>
                </div>

                {{-- Motivation --}}
                <div class="relative overflow-hidden rounded-2xl border border-blue-100 bg-gradient-to-br from-blue-50 to-white p-5 shadow-sm sm:p-6">
                    <div class="absolute -right-7 -top-8 h-28 w-28 rounded-full bg-blue-100/70"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-blue-700 shadow-sm">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="m12 3 2.3 5.7L20 11l-5.7 2.3L12 19l-2.3-5.7L4 11l5.7-2.3L12 3ZM19 16l1.2 2.8L23 20l-2.8 1.2L19 24l-1.2-2.8L15 20l2.8-1.2L19 16Z" />
                                </svg>
                            </div>
                            <span class="rounded-full bg-white px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-blue-700">Keep Growing</span>
                        </div>
                        <p class="mt-5 text-sm font-semibold text-slate-500">Ruang untuk berkembang</p>
                        <h3 class="mt-1 text-xl font-extrabold tracking-tight text-slate-800">Belajar setiap hari</h3>
                        <p class="mt-2 text-xs leading-5 text-slate-500">Langkah kecil hari ini membawa perubahan besar di masa depan.</p>
                    </div>
                </div>
            </section>

            {{-- JOIN CLASS --}}
            <section class="relative overflow-hidden rounded-3xl border border-blue-100 bg-white shadow-sm">
                <div class="absolute inset-y-0 left-0 w-1.5 bg-gradient-to-b from-blue-600 to-cyan-400"></div>
                <div class="grid gap-6 p-5 sm:p-7 lg:grid-cols-[1fr_auto] lg:items-center">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-700">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M12 15V3m0 0L8 7m4-4 4 4M5 13v6a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-6" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-[.18em] text-blue-600">Join a classroom</span>
                            <h2 class="mt-1 text-lg font-extrabold tracking-tight text-slate-800">Punya kode kelas?</h2>
                            <p class="mt-1 max-w-xl text-sm leading-6 text-slate-500">
                                Masukkan kode yang diberikan oleh guru untuk bergabung ke kelas dan mulai mengakses materi pembelajaran.
                            </p>
                        </div>
                    </div>

                    <form action="{{ route('siswa.classes.join') }}" method="POST" class="flex w-full flex-col gap-2.5 sm:flex-row lg:w-auto">
                        @csrf
                        <label for="join_code" class="sr-only">Kode kelas</label>
                        <input
                            id="join_code"
                            name="join_code"
                            value="{{ old('join_code') }}"
                            maxlength="8"
                            required
                            autocomplete="off"
                            placeholder="CONTOH: MAT12A1"
                            class="min-w-0 rounded-xl border-slate-200 bg-slate-50 px-4 py-3.5 text-sm font-extrabold uppercase tracking-[.16em] text-slate-700 placeholder:font-medium placeholder:tracking-normal focus:border-blue-500 focus:bg-white focus:ring-blue-500 sm:w-56">

                        <button type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-700 px-5 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-200 transition hover:-translate-y-0.5 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-100">
                            Gabung kelas
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-6-6 6 6-6 6" />
                            </svg>
                        </button>
                    </form>
                </div>
            </section>

            {{-- CLASS LIST --}}
            <section id="daftar-kelas" class="scroll-mt-8 space-y-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="h-1.5 w-7 rounded-full bg-blue-600"></span>
                            <p class="text-[10px] font-extrabold uppercase tracking-[.2em] text-blue-700">My Classroom</p>
                        </div>
                        <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-800 sm:text-3xl">Kelas yang Saya Ikuti</h2>
                        <p class="mt-2 text-sm text-slate-500">Pilih mata pelajaran untuk melihat materi, tugas, dan aktivitas pembelajaran.</p>
                    </div>

                    @if($joined->isNotEmpty())
                        <div class="relative w-full sm:max-w-xs">
                            <label for="classSearch" class="sr-only">Cari kelas</label>
                            <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <circle cx="11" cy="11" r="7" stroke-width="1.8" />
                                <path stroke-linecap="round" stroke-width="1.8" d="m16 16 4 4" />
                            </svg>
                            <input
                                id="classSearch"
                                type="search"
                                placeholder="Cari mata pelajaran..."
                                class="w-full rounded-xl border-slate-200 bg-white py-3 pl-10 pr-4 text-sm shadow-sm transition focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    @endif
                </div>

                @if($joined->isEmpty())
                    {{-- EMPTY STATE --}}
                    <div class="overflow-hidden rounded-3xl border border-dashed border-blue-200 bg-white px-6 py-16 text-center shadow-sm">
                        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-[1.75rem] bg-gradient-to-br from-blue-50 to-sky-100 text-blue-600">
                            <svg class="h-10 w-10" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v16H6.5A2.5 2.5 0 0 0 4 21V5.5ZM4 17a2.5 2.5 0 0 1 2.5-2.5H20" />
                            </svg>
                        </div>
                        <p class="mt-6 text-[10px] font-extrabold uppercase tracking-[.2em] text-blue-600">Your learning starts here</p>
                        <h3 class="mt-2 text-xl font-extrabold text-slate-800">Belum ada kelas yang diikuti</h3>
                        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                            Kelas yang kamu ikuti akan ditampilkan di halaman ini. Gunakan kode kelas dari guru untuk mulai bergabung.
                        </p>
                        <a href="#"
                            onclick="document.getElementById('join_code')?.focus(); return false;"
                            class="mt-6 inline-flex items-center gap-2 rounded-xl bg-blue-700 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-blue-200 transition hover:bg-blue-800">
                            <span>Masukkan kode kelas</span>
                            <span aria-hidden="true">→</span>
                        </a>
                    </div>
                @else
                    {{-- CLASS CARDS --}}
                    <div id="classGrid" class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                        @foreach($joined as $c)
                            <a
                                href="{{ route('siswa.classes.show', $c->cst_id) }}"
                                data-class-card
                                data-search="{{ strtolower(($c->subject->name ?? $c->subject_code).' '.($c->schoolClass->name ?? '').' '.($c->teacher->user->name ?? '')) }}"
                                class="class-card group relative flex min-w-0 flex-col overflow-hidden rounded-3xl border border-slate-200/80 bg-white shadow-sm focus:outline-none focus:ring-4 focus:ring-blue-100">

                                {{-- Card header --}}
                                <div class="relative overflow-hidden bg-gradient-to-br from-[#eaf3ff] via-[#f1f7ff] to-[#f8fbff] px-5 pb-6 pt-5 sm:px-6">
                                    <div class="absolute -right-9 -top-12 h-36 w-36 rounded-full bg-blue-100/70 transition duration-500 group-hover:scale-125"></div>
                                    <div class="absolute -bottom-16 right-20 h-28 w-28 rounded-full bg-sky-100/60"></div>

                                    <div class="relative flex items-start justify-between gap-3">
                                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-blue-700 shadow-sm ring-1 ring-blue-100">
                                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v16H6.5A2.5 2.5 0 0 0 4 21V5.5ZM4 17a2.5 2.5 0 0 1 2.5-2.5H20" />
                                            </svg>
                                        </div>

                                        <span class="rounded-full border border-blue-100 bg-white/90 px-3 py-1.5 text-[10px] font-extrabold uppercase tracking-wider text-blue-700 shadow-sm">
                                            {{ ucfirst($c->semester ?? 'Semester') }}
                                        </span>
                                    </div>

                                    <div class="relative mt-6">
                                        <p class="text-[10px] font-extrabold uppercase tracking-[.2em] text-blue-600">Mata Pelajaran</p>
                                        <h3 class="mt-2 line-clamp-2 min-h-[3.5rem] text-xl font-extrabold leading-snug tracking-tight text-slate-800 transition group-hover:text-blue-700">
                                            {{ $c->subject->name ?? $c->subject_code }}
                                        </h3>
                                        <div class="mt-3 inline-flex items-center gap-2 rounded-lg bg-white/80 px-3 py-1.5 text-xs font-semibold text-slate-600 ring-1 ring-blue-100/80">
                                            <svg class="h-3.5 w-3.5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 21h18M5 21V7l7-4 7 4v14" />
                                            </svg>
                                            {{ $c->schoolClass->name ?? 'Kelas '.$c->class_id }}
                                        </div>
                                    </div>
                                </div>

                                {{-- Card body --}}
                                <div class="flex flex-1 flex-col p-5 sm:p-6">
                                    {{-- Teacher --}}
                                    <div class="flex min-w-0 items-center gap-3">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-600">
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2m8-10a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" />
                                            </svg>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Pengajar</p>
                                            <p class="mt-0.5 truncate text-sm font-bold text-slate-700">
                                                {{ $c->teacher->user->name ?? 'Belum tersedia' }}
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Learning statistics --}}
                                    <div class="mt-6 grid grid-cols-3 gap-2">
                                        <div class="rounded-2xl border border-blue-100/70 bg-blue-50/70 px-2 py-3.5 text-center transition group-hover:bg-blue-50">
                                            <div class="mx-auto flex h-7 w-7 items-center justify-center rounded-lg bg-white text-blue-700 shadow-sm">
                                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v16H6.5A2.5 2.5 0 0 0 4 21V5.5Z" />
                                                </svg>
                                            </div>
                                            <p class="mt-2 text-xl font-extrabold text-blue-700">{{ $c->materials_count ?? 0 }}</p>
                                            <p class="mt-0.5 text-[10px] font-bold text-slate-500">Materi</p>
                                        </div>

                                        <div class="rounded-2xl border border-indigo-100/70 bg-indigo-50/70 px-2 py-3.5 text-center transition group-hover:bg-indigo-50">
                                            <div class="mx-auto flex h-7 w-7 items-center justify-center rounded-lg bg-white text-indigo-700 shadow-sm">
                                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" />
                                                </svg>
                                            </div>
                                            <p class="mt-2 text-xl font-extrabold text-indigo-700">{{ $c->assignments_count ?? 0 }}</p>
                                            <p class="mt-0.5 text-[10px] font-bold text-slate-500">Tugas</p>
                                        </div>

                                        <div class="rounded-2xl border border-sky-100/70 bg-sky-50/70 px-2 py-3.5 text-center transition group-hover:bg-sky-50">
                                            <div class="mx-auto flex h-7 w-7 items-center justify-center rounded-lg bg-white text-sky-700 shadow-sm">
                                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2H9V5Z" />
                                                    <path stroke-linecap="round" stroke-width="1.8" d="m9 14 2 2 4-4" />
                                                </svg>
                                            </div>
                                            <p class="mt-2 text-xl font-extrabold text-sky-700">{{ $c->exams_count ?? 0 }}</p>
                                            <p class="mt-0.5 text-[10px] font-bold text-slate-500">Ujian</p>
                                        </div>
                                    </div>

                                    {{-- Card footer --}}
                                    <div class="mt-auto flex items-center justify-between gap-3 border-t border-slate-100 pt-4 mt-5">
                                        <div class="min-w-0">
                                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Tahun ajaran</p>
                                            <p class="mt-0.5 truncate text-xs font-semibold text-slate-600">
                                                {{ $c->academic_year ?? '-' }}
                                            </p>
                                        </div>

                                        <span class="class-arrow inline-flex shrink-0 items-center gap-2 rounded-xl bg-blue-50 px-3.5 py-2.5 text-xs font-extrabold text-blue-700">
                                            Buka kelas
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-6-6 6 6-6 6" />
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    {{-- SEARCH EMPTY RESULT --}}
                    <div id="noClassResult" class="hidden rounded-2xl border border-slate-200 bg-white px-6 py-10 text-center shadow-sm">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-500">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <circle cx="11" cy="11" r="7" stroke-width="1.8" />
                                <path stroke-linecap="round" stroke-width="1.8" d="m16 16 4 4" />
                            </svg>
                        </div>
                        <h3 class="mt-3 font-bold text-slate-800">Kelas tidak ditemukan</h3>
                        <p class="mt-1 text-sm text-slate-500">Coba gunakan kata kunci mata pelajaran atau nama guru yang berbeda.</p>
                    </div>
                @endif
            </section>

            {{-- FOOTER --}}
            <footer class="border-t border-slate-200/80 pt-6">
                <div class="flex flex-col items-center justify-between gap-2 text-center sm:flex-row sm:text-left">
                    <div>
                        <p class="text-xs font-extrabold tracking-wide text-slate-600">SMAS METHODIST BANDA ACEH</p>
                        <p class="mt-1 text-[11px] text-slate-400">E-Class Student Portal</p>
                    </div>
                    <p class="text-[11px] font-medium text-slate-400">Learn today. Lead tomorrow.</p>
                </div>
            </footer>
        </div>
    </div>

    {{-- CLASS SEARCH SCRIPT --}}
    @if($joined->isNotEmpty())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const search = document.getElementById('classSearch');
                const cards = Array.from(document.querySelectorAll('[data-class-card]'));
                const empty = document.getElementById('noClassResult');

                if (!search) return;

                search.addEventListener('input', function () {
                    const keyword = this.value.trim().toLowerCase();
                    let visible = 0;

                    cards.forEach(card => {
                        const searchableText = card.dataset.search || '';
                        const match = searchableText.includes(keyword);

                        card.classList.toggle('hidden', !match);

                        if (match) visible++;
                    });

                    if (empty) {
                        empty.classList.toggle('hidden', visible !== 0);
                    }
                });
            });
        </script>
    @endif
</x-app-layout>