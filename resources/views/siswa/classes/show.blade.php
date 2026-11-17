<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <div class="flex min-w-0 items-center gap-3">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-blue-600 text-white shadow-lg shadow-blue-200">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <h2 class="truncate text-lg font-extrabold tracking-tight text-slate-800">Ruang Kelas</h2>
                    <p class="truncate text-xs font-medium text-slate-500">E-Class Student Portal</p>
                </div>
            </div>

            <a href="{{ route('siswa.classes.index') }}"
                class="hidden shrink-0 items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700 sm:inline-flex">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 18-6-6 6-6"/>
                </svg>
                Semua kelas
            </a>
        </div>
    </x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap');

        .classroom-page {
            font-family: 'DM Sans', sans-serif;
        }

        .classroom-page .rich {
            overflow-wrap: anywhere;
            font-size: .9rem;
            line-height: 1.8;
            color: #475569;
        }

        .classroom-page .rich p {
            margin: .4rem 0;
        }

        .classroom-page .rich ul {
            list-style: disc;
            padding-left: 1.35rem;
            margin: .5rem 0;
        }

        .classroom-page .rich ol {
            list-style: decimal;
            padding-left: 1.35rem;
            margin: .5rem 0;
        }

        .classroom-page .rich h1,
        .classroom-page .rich h2 {
            font-size: 1.1rem;
            font-weight: 800;
            color: #1e293b;
            margin: .9rem 0 .4rem;
        }

        .classroom-page .rich h3 {
            font-size: 1rem;
            font-weight: 700;
            color: #334155;
            margin: .7rem 0 .3rem;
        }

        .classroom-page .rich a {
            color: #0369a1;
            text-decoration: underline;
        }

        .classroom-page .rich blockquote {
            border-left: 3px solid #60a5fa;
            padding-left: .9rem;
            color: #64748b;
            font-style: italic;
            margin: .7rem 0;
        }

        .classroom-page .rich img {
            max-width: 100%;
            height: auto;
            border-radius: .85rem;
            margin: .75rem 0;
        }

        .classroom-page .rich table {
            width: 100%;
            border-collapse: collapse;
            margin: .8rem 0;
        }

        .classroom-page .rich td,
        .classroom-page .rich th {
            border: 1px solid #e2e8f0;
            padding: .5rem;
        }

        .classroom-page .section-card {
            border: 1px solid #e2e8f0;
            border-radius: 1.5rem;
            background: white;
            box-shadow: 0 8px 30px -20px rgba(15, 23, 42, .2);
            overflow: hidden;
        }

        .classroom-page .content-row {
            transition: background .2s ease;
        }

        .classroom-page .content-row:hover {
            background: #f8fbff;
        }

        .classroom-page .hero-pattern {
            background-image:
                radial-gradient(circle at 85% 15%, rgba(255,255,255,.12), transparent 25%),
                radial-gradient(circle at 70% 100%, rgba(56,189,248,.18), transparent 30%);
        }

        @media (prefers-reduced-motion: reduce) {
            .classroom-page *,
            .classroom-page *::before,
            .classroom-page *::after {
                transition-duration: .01ms !important;
            }
        }
    </style>

    <div class="classroom-page min-h-screen bg-[#f5f8fd] pb-14">
        <div class="mx-auto max-w-[1440px] space-y-8 px-4 py-6 sm:px-6 lg:px-9 lg:py-9">

            {{-- MOBILE BACK BUTTON --}}
            <a href="{{ route('siswa.classes.index') }}"
                class="inline-flex items-center gap-2 text-sm font-bold text-blue-700 transition hover:gap-3 sm:hidden">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 18-6-6 6-6"/>
                </svg>
                Kembali ke semua kelas
            </a>

            {{-- ALERTS --}}
            @if(session('success'))
                <div role="status" class="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800">
                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-emerald-100 font-bold">✓</span>
                    <span class="pt-1">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div role="alert" class="flex items-start gap-3 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-medium text-rose-800">
                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-rose-100 font-bold">!</span>
                    <span class="pt-1">{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div role="alert" class="flex items-start gap-3 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-medium text-rose-800">
                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-rose-100 font-bold">!</span>
                    <span class="pt-1">{{ $errors->first() }}</span>
                </div>
            @endif

            {{-- CLASS HERO --}}
            <section class="hero-pattern relative isolate overflow-hidden rounded-[2rem] bg-gradient-to-br from-[#102d5e] via-[#1554a5] to-[#1685d4] px-6 py-8 text-white shadow-2xl shadow-blue-200/60 sm:px-9 sm:py-10 lg:px-12 lg:py-12">
                <div class="pointer-events-none absolute -right-20 -top-28 h-80 w-80 rounded-full border-[42px] border-white/[0.07]"></div>
                <div class="pointer-events-none absolute -bottom-36 right-[25%] h-80 w-80 rounded-full bg-cyan-300/10 blur-3xl"></div>
                <div class="pointer-events-none absolute right-[8%] top-[40%] h-32 w-32 rounded-full border border-white/10"></div>

                <div class="relative grid items-center gap-9 lg:grid-cols-[1fr_280px]">
                    <div class="max-w-3xl">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3.5 py-2 text-[10px] font-extrabold uppercase tracking-[.16em] text-blue-50 backdrop-blur sm:text-xs">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-300"></span>
                                {{ $cst->schoolClass->name ?? 'Ruang Kelas' }}
                            </span>

                            <span class="rounded-full border border-white/15 bg-white/10 px-3.5 py-2 text-[10px] font-bold text-white backdrop-blur sm:text-xs">
                                {{ ucfirst($cst->semester ?? 'Semester') }}
                            </span>
                        </div>

                        <p class="mt-7 text-[10px] font-extrabold uppercase tracking-[.22em] text-blue-200 sm:text-xs">
                            STUDENT CLASSROOM
                        </p>

                        <h1 class="mt-2 max-w-2xl text-3xl font-extrabold leading-tight tracking-tight sm:text-4xl lg:text-[2.8rem]">
                            {{ $cst->subject->name ?? $cst->subject_code }}
                        </h1>

                        <div class="mt-5 flex flex-wrap items-center gap-x-5 gap-y-3 text-sm text-blue-100">
                            <div class="inline-flex items-center gap-2.5">
                                <span class="flex h-9 w-9 items-center justify-center rounded-xl border border-white/15 bg-white/10">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2m8-10a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"/>
                                    </svg>
                                </span>
                                <span>
                                    <span class="block text-[10px] font-medium text-blue-200">Pengajar</span>
                                    <strong class="font-bold text-white">{{ $cst->teacher->user->name ?? 'Guru' }}</strong>
                                </span>
                            </div>

                            <div class="inline-flex items-center gap-2.5">
                                <span class="flex h-9 w-9 items-center justify-center rounded-xl border border-white/15 bg-white/10">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <rect x="3" y="4" width="18" height="17" rx="2" stroke-width="1.8"/>
                                        <path stroke-linecap="round" stroke-width="1.8" d="M16 2v4M8 2v4M3 10h18"/>
                                    </svg>
                                </span>
                                <span>
                                    <span class="block text-[10px] font-medium text-blue-200">Tahun ajaran</span>
                                    <strong class="font-bold text-white">{{ $cst->academic_year ?? '-' }}</strong>
                                </span>
                            </div>
                        </div>

                        <div class="mt-8 flex flex-wrap gap-2.5">
                            <a href="#materi" class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-3 text-xs font-extrabold text-blue-800 shadow-lg transition hover:-translate-y-0.5 hover:bg-blue-50 sm:text-sm">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v16H6.5A2.5 2.5 0 0 0 4 21V5.5Z"/>
                                </svg>
                                Materi
                            </a>

                            <a href="#tugas" class="inline-flex items-center gap-2 rounded-xl border border-white/25 bg-white/10 px-4 py-3 text-xs font-extrabold text-white backdrop-blur transition hover:-translate-y-0.5 hover:bg-white/20 sm:text-sm">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>
                                </svg>
                                Tugas
                            </a>

                            <a href="#ujian" class="inline-flex items-center gap-2 rounded-xl border border-white/25 bg-white/10 px-4 py-3 text-xs font-extrabold text-white backdrop-blur transition hover:-translate-y-0.5 hover:bg-white/20 sm:text-sm">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2H9V5Z"/>
                                </svg>
                                Ujian
                            </a>
                        </div>
                    </div>

                    {{-- HERO DECORATION --}}
                    <div class="relative hidden h-56 items-center justify-center lg:flex">
                        <div class="absolute h-56 w-56 rounded-full border border-white/10"></div>
                        <div class="absolute h-44 w-44 rounded-full border border-white/10"></div>

                        <div class="relative flex h-36 w-36 rotate-3 items-center justify-center rounded-[2rem] border border-white/20 bg-white/10 shadow-2xl backdrop-blur-xl">
                            <svg class="h-20 w-20 text-white" viewBox="0 0 100 100" fill="none">
                                <path d="M13 28.5 50 14l37 14.5v43L50 86 13 71.5v-43Z" stroke="currentColor" stroke-width="3" stroke-linejoin="round"/>
                                <path d="M13 28.5 50 44l37-15.5M50 44v42" stroke="currentColor" stroke-width="3" stroke-linejoin="round"/>
                                <path d="M32 54h12m-12 9h24" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                            </svg>
                        </div>

                        <div class="absolute right-0 top-5 rounded-2xl border border-white/15 bg-white/10 px-4 py-3 backdrop-blur-md">
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-blue-100">Learning space</p>
                            <p class="mt-1 text-sm font-extrabold">Explore & Learn</p>
                        </div>

                        <div class="absolute bottom-3 left-0 rounded-2xl border border-white/15 bg-white/10 px-4 py-3 backdrop-blur-md">
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-blue-100">Your progress</p>
                            <p class="mt-1 text-sm font-extrabold">Keep Growing ✦</p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- QUICK COUNTS --}}
            <section class="grid grid-cols-3 gap-3 sm:gap-5">
                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm transition hover:-translate-y-1 hover:shadow-lg hover:shadow-blue-100/50 sm:flex sm:items-center sm:gap-4 sm:p-6">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-700 sm:h-12 sm:w-12 sm:rounded-2xl">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v16H6.5A2.5 2.5 0 0 0 4 21V5.5Z"/>
                        </svg>
                    </div>
                    <div class="mt-3 sm:mt-0">
                        <p class="text-xl font-extrabold tracking-tight text-blue-700 sm:text-2xl">{{ $cst->materials->count() }}</p>
                        <p class="text-[10px] font-semibold text-slate-500 sm:text-xs">Materi</p>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm transition hover:-translate-y-1 hover:shadow-lg hover:shadow-indigo-100/50 sm:flex sm:items-center sm:gap-4 sm:p-6">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-700 sm:h-12 sm:w-12 sm:rounded-2xl">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>
                        </svg>
                    </div>
                    <div class="mt-3 sm:mt-0">
                        <p class="text-xl font-extrabold tracking-tight text-indigo-700 sm:text-2xl">{{ $cst->assignments->count() }}</p>
                        <p class="text-[10px] font-semibold text-slate-500 sm:text-xs">Tugas</p>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm transition hover:-translate-y-1 hover:shadow-lg hover:shadow-sky-100/50 sm:flex sm:items-center sm:gap-4 sm:p-6">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-50 text-sky-700 sm:h-12 sm:w-12 sm:rounded-2xl">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2H9V5Z"/>
                        </svg>
                    </div>
                    <div class="mt-3 sm:mt-0">
                        <p class="text-xl font-extrabold tracking-tight text-sky-700 sm:text-2xl">{{ $exams->count() }}</p>
                        <p class="text-[10px] font-semibold text-slate-500 sm:text-xs">Ujian</p>
                    </div>
                </div>
            </section>

            {{-- MATERIALS --}}
            <section id="materi" class="section-card scroll-mt-24">
                <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-7">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-700">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v16H6.5A2.5 2.5 0 0 0 4 21V5.5Z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-extrabold uppercase tracking-[.18em] text-blue-600">Learning Resources</p>
                            <h2 class="mt-1 text-lg font-extrabold tracking-tight text-slate-800">Materi Pembelajaran</h2>
                            <p class="mt-0.5 text-xs text-slate-500">Bahan belajar yang dibagikan oleh guru.</p>
                        </div>
                    </div>

                    <span class="w-fit rounded-full bg-blue-50 px-3.5 py-2 text-xs font-extrabold text-blue-700">
                        {{ $cst->materials->count() }} Materi
                    </span>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse($cst->materials->sortByDesc('uploaded_at') as $material)
                        <article class="content-row p-5 sm:p-7">
                            <div class="flex items-start gap-4">
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-700">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6Z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M14 2v6h6M8 13h8M8 17h8"/>
                                    </svg>
                                </div>

                                <div class="min-w-0 flex-1">
                                    <h3 class="break-words text-base font-extrabold text-slate-800">{{ $material->title }}</h3>

                                    @if($material->description)
                                        <div class="rich mt-3">{!! $material->description !!}</div>
                                    @endif

                                    @if($material->file_path)
                                        <a href="{{ asset('storage/'.$material->file_path) }}"
                                            target="_blank"
                                            rel="noopener"
                                            class="mt-4 inline-flex max-w-full items-center gap-2.5 rounded-xl border border-blue-100 bg-blue-50 px-4 py-3 text-xs font-extrabold text-blue-700 transition hover:border-blue-200 hover:bg-blue-100 sm:text-sm">
                                            <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3v12m0 0 4-4m-4 4-4-4M4 17v3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3"/>
                                            </svg>
                                            <span class="truncate">Buka / Unduh Materi</span>
                                            <svg class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17 17 7M7 7h10v10"/>
                                            </svg>
                                        </a>
                                    @endif

                                    <div class="mt-4 flex items-center gap-2 text-[11px] font-medium text-slate-400">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <circle cx="12" cy="12" r="9" stroke-width="1.7"/>
                                            <path stroke-linecap="round" stroke-width="1.7" d="M12 7v5l3 2"/>
                                        </svg>
                                        Dibagikan {{ $material->uploaded_at?->format('d M Y, H:i') ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="px-5 py-14 text-center">
                            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                                <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v16H6.5A2.5 2.5 0 0 0 4 21V5.5Z"/>
                                </svg>
                            </div>
                            <h3 class="mt-4 font-extrabold text-slate-800">Belum ada materi</h3>
                            <p class="mt-1 text-sm text-slate-500">Materi pembelajaran dari guru akan muncul di sini.</p>
                        </div>
                    @endforelse
                </div>
            </section>

            {{-- ASSIGNMENTS --}}
            <section id="tugas" class="section-card scroll-mt-24">
                <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-7">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-700">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-extrabold uppercase tracking-[.18em] text-indigo-600">Assignments</p>
                            <h2 class="mt-1 text-lg font-extrabold tracking-tight text-slate-800">Tugas & Pengumpulan</h2>
                            <p class="mt-0.5 text-xs text-slate-500">Pantau deadline dan status pengumpulan tugasmu.</p>
                        </div>
                    </div>

                    <span class="w-fit rounded-full bg-indigo-50 px-3.5 py-2 text-xs font-extrabold text-indigo-700">
                        {{ $cst->assignments->count() }} Tugas
                    </span>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse($cst->assignments->sortByDesc('created_at') as $assignment)
                        @php
                            $submission = $subs->get($assignment->assignment_id);
                            $expired = $assignment->due_date ? now()->gt($assignment->due_date) : false;
                            $formats = $assignment->allowed_formats ?: ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'jpg', 'png', 'zip'];
                            $accept = collect($formats)->flatMap(fn ($f) => $f === 'jpg' ? ['.jpg', '.jpeg'] : ['.'.$f])->implode(',');
                        @endphp

                        <article class="content-row p-5 sm:p-7">
                            <div class="flex items-start gap-4">
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-700">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>
                                    </svg>
                                </div>

                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h3 class="break-words text-base font-extrabold text-slate-800">{{ $assignment->title }}</h3>

                                        @if($submission)
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-[10px] font-extrabold text-emerald-700">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                                Terkumpul
                                            </span>
                                        @elseif($expired)
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-3 py-1 text-[10px] font-extrabold text-rose-700">
                                                <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                                                Deadline lewat
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-3 py-1 text-[10px] font-extrabold text-amber-700">
                                                <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                                Belum dikumpulkan
                                            </span>
                                        @endif
                                    </div>

                                    @if($assignment->description)
                                        <div class="rich mt-3">{!! $assignment->description !!}</div>
                                    @endif

                                    <div class="mt-4 flex flex-wrap gap-x-5 gap-y-2 text-xs text-slate-500">
                                        <span class="inline-flex items-center gap-1.5">
                                            <svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <rect x="3" y="4" width="18" height="17" rx="2" stroke-width="1.7"/>
                                                <path stroke-linecap="round" stroke-width="1.7" d="M16 2v4M8 2v4M3 10h18"/>
                                            </svg>
                                            <strong class="font-bold text-slate-600">Deadline:</strong>
                                            {{ $assignment->due_date?->format('d M Y, H:i') ?? 'Tidak ditentukan' }}
                                        </span>

                                        <span class="inline-flex items-center gap-1.5">
                                            <svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M20.6 13.4 13.4 20.6a2 2 0 0 1-2.8 0L3 13V3h10l7.6 7.6a2 2 0 0 1 0 2.8Z"/>
                                                <circle cx="7.5" cy="7.5" r="1" fill="currentColor"/>
                                            </svg>
                                            <strong class="font-bold text-slate-600">Format:</strong>
                                            {{ strtoupper(implode(', ', $formats)) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- SUBMISSION RESULT --}}
                            @if($submission)
                                <div class="mt-5 overflow-hidden rounded-2xl border border-emerald-200 bg-emerald-50/70">
                                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-emerald-100 px-4 py-4 sm:px-5">
                                        <div class="flex items-center gap-2.5">
                                            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-white text-emerald-600 shadow-sm">
                                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 12 4 4L19 6"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-extrabold text-emerald-800">Jawaban berhasil dikirim</p>
                                                <p class="text-[11px] text-emerald-700">Pengumpulan tugas tercatat di sistem.</p>
                                            </div>
                                        </div>
                                        <span class="text-xs font-semibold text-emerald-700">
                                            {{ $submission->submitted_at?->format('d M Y, H:i') ?? '-' }}
                                        </span>
                                    </div>

                                    <div class="p-4 sm:p-5">
                                        @if($submission->file_path)
                                            <div class="flex flex-wrap items-center justify-between gap-2 rounded-xl border border-emerald-100 bg-white px-4 py-3">
                                                <div class="flex min-w-0 items-center gap-2">
                                                    <svg class="h-5 w-5 shrink-0 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6Z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M14 2v6h6"/>
                                                    </svg>
                                                    <span class="truncate text-xs font-bold text-slate-700">{{ basename($submission->file_path) }}</span>
                                                </div>
                                                <span class="text-[10px] font-bold text-emerald-700">File terkirim</span>
                                            </div>
                                        @endif

                                        @if($submission->score !== null)
                                            <div class="mt-4 flex items-center gap-4">
                                                <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-white text-xl font-extrabold text-emerald-700 shadow-sm">
                                                    {{ rtrim(rtrim(number_format((float)$submission->score, 2, '.', ''), '0'), '.') }}
                                                </div>
                                                <div>
                                                    <p class="text-[10px] font-extrabold uppercase tracking-[.16em] text-emerald-700">Nilai tugas</p>
                                                    <p class="mt-1 text-sm font-bold text-emerald-900">
                                                        {{ $submission->score }} / {{ $assignment->max_score }}
                                                    </p>
                                                </div>
                                            </div>
                                        @else
                                            <p class="mt-4 text-xs font-medium text-emerald-700">Jawaban sedang menunggu pemeriksaan guru.</p>
                                        @endif

                                        @if($submission->feedback)
                                            <div class="mt-4 rounded-xl border border-emerald-100 bg-white p-4">
                                                <p class="text-[10px] font-extrabold uppercase tracking-[.16em] text-emerald-700">Feedback guru</p>
                                                <p class="mt-2 text-sm leading-6 text-slate-700">{{ $submission->feedback }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                            {{-- UPLOAD FORM --}}
                            @elseif(!$expired)
                                <form action="{{ route('siswa.classes.assignments.submit', $assignment) }}"
                                    method="POST"
                                    enctype="multipart/form-data"
                                    class="mt-5 rounded-2xl border border-blue-100 bg-blue-50/60 p-4 sm:p-5">
                                    @csrf

                                    <div class="mb-3">
                                        <p class="text-sm font-extrabold text-slate-800">Kumpulkan jawaban tugas</p>
                                        <p class="mt-1 text-xs text-slate-500">Pilih file jawaban sesuai format yang ditentukan guru.</p>
                                    </div>

                                    <label for="assignment-file-{{ $assignment->assignment_id }}" class="sr-only">Unggah jawaban</label>

                                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                                        <input
                                            id="assignment-file-{{ $assignment->assignment_id }}"
                                            type="file"
                                            name="file"
                                            required
                                            data-allowed="{{ implode(',', $formats) }}"
                                            data-max-mb="20"
                                            accept="{{ $accept }}"
                                            class="min-w-0 w-full rounded-xl border border-slate-200 bg-white p-2 text-xs text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-blue-100 file:px-3 file:py-2 file:text-xs file:font-bold file:text-blue-700 hover:file:bg-blue-200 sm:flex-1">

                                        <button type="submit"
                                            class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-blue-700 px-5 py-3 text-sm font-extrabold text-white shadow-lg shadow-blue-200 transition hover:-translate-y-0.5 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-100">
                                            Kumpulkan tugas
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 16V4m0 0L8 8m4-4 4 4M4 16v4h16v-4"/>
                                            </svg>
                                        </button>
                                    </div>

                                    <p class="mt-3 text-[11px] font-medium text-slate-500">
                                        Format: {{ strtoupper(implode(', ', $formats)) }} · Maksimal 20 MB.
                                    </p>
                                </form>

                            {{-- EXPIRED --}}
                            @else
                                <div class="mt-5 flex items-start gap-3 rounded-2xl border border-rose-100 bg-rose-50 p-4">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white text-rose-600">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <circle cx="12" cy="12" r="9" stroke-width="1.8"/>
                                            <path stroke-linecap="round" stroke-width="1.8" d="M12 7v5l3 2"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-extrabold text-rose-800">Waktu pengumpulan telah berakhir</p>
                                        <p class="mt-1 text-xs leading-5 text-rose-700">Batas waktu tugas ini sudah terlewati dan pengumpulan tidak tersedia.</p>
                                    </div>
                                </div>
                            @endif
                        </article>
                    @empty
                        <div class="px-5 py-14 text-center">
                            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600">
                                <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M8 6h13M8 12h13M8 18h13"/>
                                </svg>
                            </div>
                            <h3 class="mt-4 font-extrabold text-slate-800">Belum ada tugas</h3>
                            <p class="mt-1 text-sm text-slate-500">Tugas yang diberikan guru akan muncul di bagian ini.</p>
                        </div>
                    @endforelse
                </div>
            </section>

            {{-- EXAMS --}}
            <section id="ujian" class="section-card scroll-mt-24">
                <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-7">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-sky-50 text-sky-700">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2H9V5Z"/>
                                <path stroke-linecap="round" stroke-width="1.8" d="m9 14 2 2 4-4"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-extrabold uppercase tracking-[.18em] text-sky-600">Assessment Center</p>
                            <h2 class="mt-1 text-lg font-extrabold tracking-tight text-slate-800">Ujian & Kuis</h2>
                            <p class="mt-0.5 text-xs text-slate-500">Daftar ujian yang telah dipublikasikan oleh guru.</p>
                        </div>
                    </div>

                    <span class="w-fit rounded-full bg-sky-50 px-3.5 py-2 text-xs font-extrabold text-sky-700">
                        {{ $exams->count() }} Ujian
                    </span>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse($exams as $exam)
                        @php
                            $session = $sessions->get($exam->exam_id);
                            $start = \Illuminate\Support\Carbon::parse($exam->start_time);
                            $end = \Illuminate\Support\Carbon::parse($exam->end_time);

                            if ($session && $session->status !== 'berlangsung') {
                                $statusLabel = 'Selesai';
                                $statusClass = 'bg-emerald-50 text-emerald-700';
                                $statusDot = 'bg-emerald-500';
                            } elseif (now()->lt($start)) {
                                $statusLabel = 'Belum dibuka';
                                $statusClass = 'bg-blue-50 text-blue-700';
                                $statusDot = 'bg-blue-500';
                            } elseif (now()->gt($end)) {
                                $statusLabel = 'Ditutup';
                                $statusClass = 'bg-slate-100 text-slate-600';
                                $statusDot = 'bg-slate-400';
                            } elseif ($session) {
                                $statusLabel = 'Sedang berlangsung';
                                $statusClass = 'bg-amber-50 text-amber-700';
                                $statusDot = 'bg-amber-500';
                            } else {
                                $statusLabel = 'Tersedia';
                                $statusClass = 'bg-sky-50 text-sky-700';
                                $statusDot = 'bg-sky-500';
                            }
                        @endphp

                        <article class="content-row p-5 sm:p-7">
                            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                                <div class="flex min-w-0 items-start gap-4">
                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-sky-50 text-sky-700">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2H9V5Z"/>
                                            <path stroke-linecap="round" stroke-width="1.7" d="m9 14 2 2 4-4"/>
                                        </svg>
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <h3 class="break-words text-base font-extrabold text-slate-800">{{ $exam->title }}</h3>
                                            <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-[10px] font-extrabold {{ $statusClass }}">
                                                <span class="h-1.5 w-1.5 rounded-full {{ $statusDot }}"></span>
                                                {{ $statusLabel }}
                                            </span>
                                        </div>

                                        <div class="mt-3 flex flex-wrap gap-x-5 gap-y-2 text-xs text-slate-500">
                                            <span class="inline-flex items-center gap-1.5">
                                                <svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M4 5h16v14H4zM8 3v4m8-4v4M4 10h16"/>
                                                </svg>
                                                {{ strtoupper($exam->exam_type) }}
                                            </span>

                                            <span class="inline-flex items-center gap-1.5">
                                                <svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>
                                                </svg>
                                                {{ $exam->questions_count }} soal
                                            </span>

                                            <span class="inline-flex items-center gap-1.5">
                                                <svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                    <circle cx="12" cy="12" r="9" stroke-width="1.7"/>
                                                    <path stroke-linecap="round" stroke-width="1.7" d="M12 7v5l3 2"/>
                                                </svg>
                                                {{ $start->format('d M Y, H:i') }}
                                            </span>
                                        </div>

                                        <div class="mt-2 text-xs text-slate-400">
                                            Berakhir: {{ $end->format('d M Y, H:i') }}
                                        </div>
                                    </div>
                                </div>

                                <a href="{{ route('siswa.exams.show', $exam) }}"
                                    class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-blue-700 px-5 py-3 text-sm font-extrabold text-white shadow-lg shadow-blue-200 transition hover:-translate-y-0.5 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-100">
                                    Lihat ujian
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-6-6 6 6-6 6"/>
                                    </svg>
                                </a>
                            </div>
                        </article>
                    @empty
                        <div class="px-5 py-14 text-center">
                            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-sky-50 text-sky-600">
                                <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                                </svg>
                            </div>
                            <h3 class="mt-4 font-extrabold text-slate-800">Belum ada ujian atau kuis</h3>
                            <p class="mt-1 text-sm text-slate-500">Ujian yang dipublikasikan guru akan ditampilkan di sini.</p>
                        </div>
                    @endforelse
                </div>
            </section>

            {{-- BOTTOM NAVIGATION --}}
            <div class="flex flex-col items-center justify-between gap-3 border-t border-slate-200 pt-6 sm:flex-row">
                <a href="{{ route('siswa.classes.index') }}"
                    class="inline-flex items-center gap-2 text-sm font-extrabold text-blue-700 transition hover:gap-3 hover:text-blue-900">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 18-6-6 6-6"/>
                    </svg>
                    Kembali ke daftar kelas
                </a>

                <p class="text-xs font-medium text-slate-400">
                    SMAS Methodist Banda Aceh · E-Class Student Portal
                </p>
            </div>
        </div>
    </div>

    {{-- FILE VALIDATION --}}
    <script src="{{ asset('js/file-validator.js') }}"></script>
</x-app-layout>