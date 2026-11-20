<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1.5 py-0.5">
            <div>
                <p class="text-[10px] sm:text-xs font-bold uppercase tracking-[.2em] text-[#267BB5]">Methodist Learning Space</p>
                <h2 class="font-bold text-xl text-slate-800 leading-tight">
                    {{ __('E-Exam & Kuis') }}
                </h2>
            </div>
            <div>
                <span class="inline-flex items-center gap-1.5 rounded-full border border-indigo-100 bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-800 shadow-sm">
                    <span class="h-2 w-2 rounded-full bg-indigo-500"></span>
                    Asesmen &amp; Ujian Digital Guru
                </span>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-[#FAF9F6] py-8">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">

            {{-- BREADCRUMB & TOMBOL AKSI (DI BAWAH HEADER) --}}
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-2 text-xs font-medium text-slate-400">
                    <a href="{{ route('guru.dashboard') }}" class="transition hover:text-blue-600">
                        Dashboard
                    </a>
                    <span>/</span>
                    <span class="font-bold text-blue-700">E-Exam &amp; Kuis</span>
                </div>

                <div class="flex flex-wrap items-center gap-2.5">
                    <a href="{{ route('guru.dashboard') }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-600 shadow-sm transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">
                        <span>←</span>
                        <span>Kembali ke Dashboard</span>
                    </a>
                    <a href="{{ route('guru.exams.create') }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-[#123B60] px-4 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-[#267BB5]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                        </svg>
                        <span>Tambah Ujian Baru</span>
                    </a>
                </div>
            </div>

            <!-- Flash Message / Notifikasi (Opsional jika ada) -->
            @if (session('success'))
                <div x-data="{ show: true }" 
                x-init="setTimeout(() => show = false, 3000)" 
                x-show="show" 
                x-transition.duration.500ms
                class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-800 shadow-sm">
                    {{ session('success') }}
                </div>
            @elseif (session('error'))
                <div x-data="{ show: true }" 
                    x-init="setTimeout(() => show = false, 4000)" 
                    x-show="show" 
                    x-transition.duration.500ms
                    class="mb-4 rounded-xl bg-rose-50 border border-rose-200 px-4 py-3 text-sm text-rose-700">
                    {{ session('error') }}
                </div>
            @endif

            <!-- List Exams -->
            <div class="grid grid-cols-1 gap-4">
                @forelse ($exams as $exam)
                    <article class="group relative flex flex-col gap-5 rounded-2xl border border-[#E7EDF2] bg-white p-6 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg sm:flex-row sm:items-center sm:justify-between">
                        
                        <!-- Informasi Ujian -->
                        <div class="space-y-1.5">
                            <div class="flex items-center gap-3">
                                <h4 class="text-base font-semibold text-[#123B60] transition-colors group-hover:text-[#267BB5]">
                                    {{ $exam->title }}
                                </h4>
                                
                                <!-- Status Badge -->
                                @if($exam->is_published)
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                        {{ __('Dipublikasikan') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-600/20">
                                        {{ __('Draft') }}
                                    </span>
                                @endif
                            </div>

                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500 font-medium">
                                <span class="flex items-center gap-1 text-slate-700">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    {{ $exam->classSubjectTeacher->schoolClass->name }}
                                </span>
                                <span>·</span>
                                <span class="font-semibold text-[#267BB5]">
                                    {{ $exam->classSubjectTeacher->subject->name }}
                                </span>
                                <span>·</span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Mulai: {{ $exam->start_time->format('d M Y, H:i') }}
                                </span>
                            </div>
                        </div>

                        <!-- Aksi (Tombol Kelola, Edit, dan Hapus) -->
                        <div class="mt-4 sm:mt-0 flex items-center gap-2">
                            <!-- Tombol Kelola -->
                            <a href="{{ route('guru.exams.show', $exam) }}" 
                            class="inline-flex items-center justify-center rounded-full bg-[#E8F1F8] px-4 py-2 text-xs font-semibold text-[#123B60] transition-colors hover:bg-[#D9EAF5]">
                                {{ __('Kelola') }}
                            </a>

                            
                            <!-- Tombol Edit (Opsional jika ingin cepat) -->
                            <a href="{{ route('guru.exams.edit', $exam) }}" 
                            class="inline-flex items-center justify-center rounded-xl bg-slate-100 px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition-colors">
                                {{ __('Edit') }}
                            </a>
                            
                            <form action="{{ route('guru.exams.toggle-publish', $exam) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')

                                @if ($exam->is_published)
                                        <!-- Status: Sedang Published -> Opsi: Unpublish -->
                                        <button type="submit" 
                                                onclick="return confirm('Yakin ingin unpublish ujian ini?')"
                                                class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 hover:bg-rose-50 hover:text-rose-700 transition-colors">
                                                Unpublish
                                        </button>
                                    @else
                                        <!-- Status: Draft -> Opsi: Publish -->
                                        <button type="submit" 
                                                class="inline-flex items-center gap-1.5 rounded-xl bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                                            Publish
                                        </button>
                                    @endif
                            </form>
                            
                            <a href="{{ route('guru.exams.results', $exam) }}" class="text-xs font-bold text-emerald-700 bg-emerald-100 hover:bg-emerald-200 px-3 py-1.5 rounded-lg">
                                Periksa Hasil Siswa
                            </a>
                            
                            <!-- Tombol Hapus dengan Form & Konfirmasi -->
                            <form action="{{ route('guru.exams.destroy', $exam) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ujian ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="inline-flex items-center justify-center rounded-xl bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-100 transition-colors"
                                        title="Hapus Ujian">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </article>
                @empty
                    <!-- Empty State yang Lebih Menarik -->
                    <div class="rounded-2xl bg-white p-12 text-center shadow-sm ring-1 ring-slate-200">
                        <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-[#E8F1F8] text-[#267BB5]">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-slate-900">{{ __('Belum ada ujian atau kuis') }}</h3>
                        <p class="mt-1 text-sm text-slate-500">{{ __('Mulai buat evaluasi pembelajaran untuk kelas Anda sekarang.') }}</p>
                        <div class="mt-6">
                            <a href="{{ route('guru.exams.create') }}" class="inline-flex items-center gap-2 rounded-full bg-[#123B60] px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#0B2B48]">
                                {{ __('Buat Ujian Pertama') }}
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $exams->links() }}
            </div>
        </div>
    </div>
</x-app-layout>