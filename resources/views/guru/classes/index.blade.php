
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            E-Class Guru
        </h2>
    </x-slot>

    <div class="min-h-screen bg-slate-50 py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">

            {{-- HERO SECTION --}}
            <div class="overflow-hidden rounded-3xl bg-gradient-to-r from-sky-600 to-blue-500 p-7 text-white shadow-lg">
                <p class="text-sm font-medium text-sky-100">
                    Ruang belajar digital
                </p>

                <h1 class="mt-1 text-3xl font-bold">
                    Kelas yang Saya Ajar
                </h1>

                <p class="mt-2 text-sm text-sky-100">
                    Kelola siswa, materi pembelajaran, tugas, dan aktivitas kelas dalam satu tempat.
                </p>

                <div class="mt-5 inline-flex items-center gap-2 rounded-xl bg-white/15 px-4 py-2 text-sm font-semibold">
                    <span>📚</span>
                    {{ $classes->count() }} kelas aktif
                </div>
            </div>

            {{-- DAFTAR KELAS --}}
            @if ($classes->isEmpty())

                <div class="rounded-2xl border border-slate-200 bg-white p-10 text-center shadow-sm">
                    <div class="text-5xl">📚</div>

                    <h3 class="mt-3 text-lg font-bold text-slate-800">
                        Belum Ada Kelas
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        Kelas yang ditugaskan kepada Anda akan muncul di sini.
                    </p>
                </div>

            @else

                <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">

                    @foreach ($classes as $class)

                        <a href="{{ route('guru.classes.show', $class->cst_id) }}"
                           class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg">

                            {{-- HEADER KARTU --}}
                            <div class="border-b border-sky-100 bg-gradient-to-r from-sky-50 to-blue-50 p-5">

                                <div class="flex items-start justify-between gap-3">

                                    <div>
                                        <span class="text-xs font-semibold uppercase tracking-wide text-sky-600">
                                            Mata Pelajaran
                                        </span>

                                        <h3 class="mt-1 text-xl font-bold text-slate-800 transition group-hover:text-sky-700">
                                            {{ $class->subject->name ?? $class->subject_code }}
                                        </h3>

                                        <p class="mt-1 text-sm text-slate-600">
                                            {{ $class->schoolClass->name ?? 'Kelas '.$class->class_id }}
                                        </p>
                                    </div>

                                    <span class="rounded-xl bg-white p-3 text-2xl shadow-sm">
                                        📘
                                    </span>

                                </div>
                            </div>

                            {{-- STATISTIK --}}
                            <div class="p-5">

                                <div class="grid grid-cols-3 gap-2 text-center">

                                    <div class="rounded-xl bg-sky-50 p-3">
                                        <p class="text-xl font-bold text-sky-700">
                                            {{ $class->joined_students_count ?? 0 }}
                                        </p>
                                        <p class="text-xs text-slate-500">
                                            Siswa
                                        </p>
                                    </div>

                                    <div class="rounded-xl bg-blue-50 p-3">
                                        <p class="text-xl font-bold text-blue-700">
                                            {{ $class->materials_count ?? 0 }}
                                        </p>
                                        <p class="text-xs text-slate-500">
                                            Materi
                                        </p>
                                    </div>

                                    <div class="rounded-xl bg-indigo-50 p-3">
                                        <p class="text-xl font-bold text-indigo-700">
                                            {{ $class->assignments_count ?? 0 }}
                                        </p>
                                        <p class="text-xs text-slate-500">
                                            Tugas
                                        </p>
                                    </div>

                                </div>

                                {{-- FOOTER KARTU --}}
                                <div class="mt-4 flex items-center justify-between gap-2 text-sm">

                                    <span class="text-slate-500">
                                        {{ $class->academic_year ?? '-' }}
                                        ·
                                        {{ ucfirst($class->semester ?? '-') }}
                                    </span>

                                    <span class="font-semibold text-sky-700 transition group-hover:text-blue-700">
                                        Buka kelas →
                                    </span>

                                </div>

                            </div>

                        </a>

                    @endforeach

                </div>

            @endif

        </div>
    </div>
</x-app-layout>
