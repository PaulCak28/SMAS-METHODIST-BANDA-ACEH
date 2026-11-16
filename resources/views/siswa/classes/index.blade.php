<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-100 text-sky-700">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/></svg>
            </div>
            <div>
                <h2 class="text-lg font-bold tracking-tight text-slate-800">E-Class Siswa</h2>
                <p class="text-xs text-slate-500">Ruang belajar digital SMAS Methodist Banda Aceh</p>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-50 pb-12">
        <div class="mx-auto max-w-7xl space-y-8 px-4 py-6 sm:px-6 lg:px-8 lg:py-9">
            {{-- HERO --}}
            <section class="relative isolate overflow-hidden rounded-[2rem] bg-gradient-to-br from-sky-700 via-sky-600 to-blue-600 px-6 py-8 text-white shadow-xl shadow-sky-100 sm:px-9 sm:py-10 lg:px-12">
                <div class="pointer-events-none absolute -right-16 -top-24 h-72 w-72 rounded-full border-[34px] border-white/10"></div>
                <div class="pointer-events-none absolute -bottom-28 right-1/4 h-64 w-64 rounded-full bg-cyan-300/10 blur-2xl"></div>
                <div class="relative grid items-center gap-8 lg:grid-cols-[1fr_auto]">
                    <div class="max-w-2xl">
                        <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3.5 py-1.5 text-xs font-semibold tracking-wide text-sky-50 backdrop-blur">
                            <span class="h-2 w-2 rounded-full bg-emerald-300 shadow-[0_0_0_4px_rgba(110,231,183,.15)]"></span>
                            STUDENT LEARNING SPACE
                        </span>
                        <h1 class="mt-5 text-3xl font-extrabold leading-tight tracking-tight sm:text-4xl lg:text-[2.7rem]">Belajar lebih terarah,<br class="hidden sm:block"> raih hasil yang terbaik.</h1>
                        <p class="mt-3 max-w-xl text-sm leading-6 text-sky-100 sm:text-base">Semua materi, tugas, dan ujian dari guru tersusun dalam satu ruang belajar. Pilih kelasmu dan lanjutkan proses belajarmu hari ini.</p>
                        <a href="#daftar-kelas" class="mt-6 inline-flex items-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-bold text-sky-700 shadow-lg shadow-sky-950/10 transition hover:-translate-y-0.5 hover:bg-sky-50 focus:outline-none focus:ring-4 focus:ring-white/30">
                            Jelajahi kelas <span aria-hidden="true">→</span>
                        </a>
                    </div>
                    <div class="hidden w-56 justify-self-center lg:flex">
                        <div class="relative flex h-48 w-48 items-center justify-center rounded-[2.5rem] border border-white/20 bg-white/10 shadow-2xl backdrop-blur-sm rotate-3">
                            <div class="absolute inset-3 rounded-[2rem] border border-white/15"></div>
                            <svg class="h-24 w-24 text-white drop-shadow-lg" viewBox="0 0 100 100" fill="none" aria-hidden="true"><path d="M13 28.5 50 14l37 14.5v43L50 86 13 71.5v-43Z" stroke="currentColor" stroke-width="3" stroke-linejoin="round"/><path d="M13 28.5 50 44l37-15.5M50 44v42M31 21.5l37 15" stroke="currentColor" stroke-width="3" stroke-linecap="round"/><path d="M32 54h12m-12 9h24" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg>
                            <span class="absolute -bottom-3 -left-5 rounded-xl bg-white px-3 py-2 text-xs font-extrabold text-sky-700 shadow-lg">KEEP LEARNING ✦</span>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ALERTS --}}
            @if(session('success'))
                <div role="status" class="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 shadow-sm"><span class="text-lg">✓</span><span>{{ session('success') }}</span></div>
            @endif
            @if(session('error'))
                <div role="alert" class="flex items-start gap-3 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800 shadow-sm"><span class="text-lg">!</span><span>{{ session('error') }}</span></div>
            @endif
            @if($errors->any())
                <div role="alert" class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800 shadow-sm">{{ $errors->first() }}</div>
            @endif

            {{-- OVERVIEW --}}
            <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div class="flex items-center gap-4 rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm shadow-slate-200/40">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-sky-100 text-2xl">📚</div>
                    <div><p class="text-sm font-medium text-slate-500">Kelas yang diikuti</p><p class="mt-0.5 text-2xl font-extrabold tracking-tight text-slate-800">{{ $joined->count() }} <span class="text-sm font-semibold text-slate-400">kelas</span></p></div>
                </div>
                <div class="flex items-center gap-4 rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm shadow-slate-200/40">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-100 text-2xl">🎓</div>
                    <div><p class="text-sm font-medium text-slate-500">Rombongan belajar</p><p class="mt-0.5 text-lg font-extrabold tracking-tight text-slate-800">{{ auth()->user()->student->schoolClass->name ?? 'Belum ditentukan' }}</p></div>
                </div>
                <div class="flex items-center gap-4 rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm shadow-slate-200/40 sm:col-span-2 lg:col-span-1">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-indigo-100 text-2xl">✨</div>
                    <div><p class="text-sm font-medium text-slate-500">Ruang untuk berkembang</p><p class="mt-0.5 text-lg font-extrabold tracking-tight text-slate-800">Belajar setiap hari</p></div>
                </div>
            </section>

            {{-- JOIN CLASS --}}
            <section class="overflow-hidden rounded-3xl border border-sky-100 bg-white shadow-sm shadow-slate-200/50">
                <div class="grid gap-5 p-5 sm:p-7 lg:grid-cols-[1fr_auto] lg:items-center">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-sky-100 text-2xl">🔑</div>
                        <div><h2 class="text-lg font-bold text-slate-800">Punya kode kelas?</h2><p class="mt-1 max-w-xl text-sm leading-6 text-slate-500">Masukkan kode yang dibagikan guru untuk bergabung ke kelas dan mulai mengakses pembelajaran.</p></div>
                    </div>
                    <form action="{{ route('siswa.classes.join') }}" method="POST" class="flex w-full flex-col gap-2 sm:flex-row lg:w-auto">
                        @csrf
                        <label for="join_code" class="sr-only">Kode kelas</label>
                        <input id="join_code" name="join_code" value="{{ old('join_code') }}" maxlength="8" required autocomplete="off" placeholder="CONTOH: MAT12A1" class="min-w-0 rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold uppercase tracking-[.18em] text-slate-700 placeholder:font-medium placeholder:tracking-normal focus:border-sky-500 focus:bg-white focus:ring-sky-500 sm:w-56">
                        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-sky-600 px-5 py-3 text-sm font-bold text-white shadow-md shadow-sky-200 transition hover:-translate-y-0.5 hover:bg-sky-700 focus:outline-none focus:ring-4 focus:ring-sky-200">Gabung kelas <span>→</span></button>
                    </form>
                </div>
            </section>

            {{-- CLASS LIST --}}
            <section id="daftar-kelas" class="scroll-mt-6 space-y-5">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div><p class="text-xs font-bold uppercase tracking-[.18em] text-sky-600">MY CLASSROOM</p><h2 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-800">Kelas yang Saya Ikuti</h2><p class="mt-1 text-sm text-slate-500">Pilih salah satu kelas untuk melihat aktivitas pembelajaran.</p></div>
                    @if($joined->isNotEmpty())
                        <div class="relative w-full sm:max-w-xs"><label for="classSearch" class="sr-only">Cari kelas</label><span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">⌕</span><input id="classSearch" type="search" placeholder="Cari mata pelajaran..." class="w-full rounded-xl border-slate-200 bg-white py-2.5 pl-9 pr-4 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500"></div>
                    @endif
                </div>

                @if($joined->isEmpty())
                    <div class="rounded-3xl border-2 border-dashed border-sky-200 bg-white px-6 py-14 text-center shadow-sm">
                        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-sky-50 text-4xl">📖</div>
                        <h3 class="mt-5 text-xl font-extrabold text-slate-800">Belum ada kelas di sini</h3>
                        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">Kelas yang kamu ikuti akan muncul di halaman ini. Masukkan kode kelas dari guru pada bagian di atas untuk memulai.</p>
                    </div>
                @else
                    <div id="classGrid" class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                        @foreach($joined as $c)
                            <a href="{{ route('siswa.classes.show', $c->cst_id) }}" data-class-card data-search="{{ strtolower(($c->subject->name ?? $c->subject_code).' '.($c->schoolClass->name ?? '').' '.($c->teacher->user->name ?? '')) }}" class="group relative flex min-w-0 flex-col overflow-hidden rounded-3xl border border-slate-200/80 bg-white shadow-sm shadow-slate-200/50 transition duration-300 hover:-translate-y-1 hover:border-sky-200 hover:shadow-xl hover:shadow-sky-100/70 focus:outline-none focus:ring-4 focus:ring-sky-100">
                                <div class="relative overflow-hidden bg-gradient-to-br from-sky-50 via-blue-50 to-white p-5 sm:p-6">
                                    <div class="absolute -right-7 -top-9 h-28 w-28 rounded-full bg-sky-100/70 transition duration-500 group-hover:scale-125"></div>
                                    <div class="relative flex items-start justify-between gap-3">
                                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-2xl shadow-sm ring-1 ring-sky-100">📘</div>
                                        <span class="rounded-full border border-sky-100 bg-white/90 px-3 py-1.5 text-[11px] font-bold uppercase tracking-wide text-sky-700">{{ ucfirst($c->semester ?? 'Semester') }}</span>
                                    </div>
                                    <div class="relative mt-5"><p class="text-[10px] font-extrabold uppercase tracking-[.2em] text-sky-600">MATA PELAJARAN</p><h3 class="mt-1 line-clamp-2 text-xl font-extrabold leading-snug text-slate-800 transition group-hover:text-sky-700">{{ $c->subject->name ?? $c->subject_code }}</h3><p class="mt-1 text-sm font-medium text-slate-500">{{ $c->schoolClass->name ?? 'Kelas '.$c->class_id }}</p></div>
                                </div>
                                <div class="flex flex-1 flex-col p-5 sm:p-6">
                                    <div class="flex min-w-0 items-center gap-3"><div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-slate-100 text-sm">👨‍🏫</div><div class="min-w-0"><p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Pengajar</p><p class="truncate text-sm font-bold text-slate-700">{{ $c->teacher->user->name ?? 'Belum tersedia' }}</p></div></div>
                                    <div class="mt-5 grid grid-cols-3 gap-2">
                                        <div class="rounded-2xl bg-sky-50 px-2 py-3 text-center"><p class="text-xl font-extrabold text-sky-700">{{ $c->materials_count ?? 0 }}</p><p class="mt-0.5 text-[11px] font-semibold text-slate-500">Materi</p></div>
                                        <div class="rounded-2xl bg-blue-50 px-2 py-3 text-center"><p class="text-xl font-extrabold text-blue-700">{{ $c->assignments_count ?? 0 }}</p><p class="mt-0.5 text-[11px] font-semibold text-slate-500">Tugas</p></div>
                                        <div class="rounded-2xl bg-indigo-50 px-2 py-3 text-center"><p class="text-xl font-extrabold text-indigo-700">{{ $c->exams_count ?? 0 }}</p><p class="mt-0.5 text-[11px] font-semibold text-slate-500">Ujian</p></div>
                                    </div>
                                    <div class="mt-auto flex items-center justify-between gap-3 border-t border-slate-100 pt-4 mt-5"><span class="truncate text-xs text-slate-400">{{ $c->academic_year ?? 'Tahun ajaran -' }}</span><span class="inline-flex shrink-0 items-center gap-1 text-sm font-extrabold text-sky-700 transition group-hover:gap-2">Buka kelas <span aria-hidden="true">→</span></span></div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    <p id="noClassResult" class="hidden rounded-2xl border border-slate-200 bg-white p-6 text-center text-sm text-slate-500">Kelas yang kamu cari tidak ditemukan.</p>
                @endif
            </section>
            <p class="pb-2 text-center text-xs text-slate-400">SMAS Methodist Banda Aceh · E-Class Student Portal</p>
        </div>
    </div>

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
                        const match = card.dataset.search.includes(keyword);
                        card.classList.toggle('hidden', !match);
                        if (match) visible++;
                    });
                    empty?.classList.toggle('hidden', visible !== 0);
                });
            });
        </script>
    @endif
</x-app-layout>
