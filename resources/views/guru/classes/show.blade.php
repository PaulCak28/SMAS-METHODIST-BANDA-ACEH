<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">E-Class</h2>
    </x-slot>

    @php
        $now = now();
        $status = function ($e) use ($now) {
            if (! $e->is_published) return ['Draf', 'bg-gray-100 text-gray-600'];
            $start = \Illuminate\Support\Carbon::parse($e->start_time);
            $end = \Illuminate\Support\Carbon::parse($e->end_time);
            if ($now->lt($start)) return ['Belum dibuka', 'bg-blue-50 text-blue-700'];
            if ($now->gt($end)) return ['Ditutup', 'bg-gray-100 text-gray-600'];
            return ['Berlangsung', 'bg-amber-50 text-amber-700'];
        };
    @endphp

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <a href="{{ route('guru.classes.index') }}" class="text-sm text-indigo-600 hover:underline">← Kembali ke daftar kelas</a>

            {{-- Header kelas --}}
            <div class="rounded-2xl p-6 text-white bg-gradient-to-r from-indigo-600 to-violet-600 shadow-sm">
                <p class="text-sm opacity-80">{{ $class->schoolClass->name ?? 'Kelas '.$class->class_id }}</p>
                <h1 class="text-2xl font-bold mt-1">{{ $class->subject->name ?? $class->subject_code }}</h1>
                <div class="mt-4 flex flex-wrap gap-x-8 gap-y-2 text-sm">
                    <span>Tahun ajaran: <strong>{{ $class->academic_year }}</strong></span>
                    <span>Semester: <strong>{{ ucfirst($class->semester) }}</strong></span>
                    <span>Kode gabung: <strong class="font-mono tracking-wider">{{ $class->join_code }}</strong></span>
                </div>
            </div>

            {{-- Form tambah materi dan tugas --}}
            <div class="grid gap-6 lg:grid-cols-2">
                <section class="rounded-2xl border border-sky-100 bg-white p-6 shadow-sm">
                    <div class="mb-4 flex items-center gap-3">
                        <span class="rounded-xl bg-sky-100 p-3 text-xl">📚</span>
                        <div><h2 class="text-lg font-bold text-slate-800">Tambah Materi</h2><p class="text-sm text-slate-500">Bagikan bahan pembelajaran ke siswa.</p></div>
                    </div>
                    <form action="{{ route('guru.classes.materials.store', $cst) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div><label class="mb-1 block text-sm font-semibold text-slate-700">Judul materi</label><input name="title" value="{{ old('title') }}" required maxlength="150" class="w-full rounded-xl border-slate-300 text-sm focus:border-sky-500 focus:ring-sky-500" placeholder="Contoh: Pengenalan Basis Data"></div>
                        <div><label class="mb-1 block text-sm font-semibold text-slate-700">Deskripsi</label><textarea name="description" rows="3" class="w-full rounded-xl border-slate-300 text-sm focus:border-sky-500 focus:ring-sky-500" placeholder="Tuliskan ringkasan materi...">{{ old('description') }}</textarea></div>
                        <div><label class="mb-1 block text-sm font-semibold text-slate-700">File materi <span class="font-normal text-slate-400">(opsional, maks. 20 MB)</span></label><input type="file" name="file" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.jpg,.jpeg,.png,.mp4,.zip" class="block w-full rounded-xl border border-slate-300 p-2 text-sm"></div>
                        <button class="w-full rounded-xl bg-sky-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-sky-700">+ Unggah Materi</button>
                    </form>
                </section>

                <section class="rounded-2xl border border-indigo-100 bg-white p-6 shadow-sm">
                    <div class="mb-4 flex items-center gap-3">
                        <span class="rounded-xl bg-indigo-100 p-3 text-xl">📝</span>
                        <div><h2 class="text-lg font-bold text-slate-800">Buat Tugas</h2><p class="text-sm text-slate-500">Buat tugas dan tentukan batas pengumpulan.</p></div>
                    </div>
                    <form action="{{ route('guru.classes.assignments.store', $cst) }}" method="POST" class="space-y-4">
                        @csrf
                        <div><label class="mb-1 block text-sm font-semibold text-slate-700">Judul tugas</label><input name="title" value="{{ old('title') }}" required maxlength="150" class="w-full rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Contoh: Latihan Pertemuan 1"></div>
                        <div><label class="mb-1 block text-sm font-semibold text-slate-700">Instruksi tugas</label><textarea name="description" rows="3" class="w-full rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Jelaskan tugas yang harus dikerjakan siswa...">{{ old('description') }}</textarea></div>
                        <div class="grid gap-3 sm:grid-cols-2">
                            <div><label class="mb-1 block text-sm font-semibold text-slate-700">Batas pengumpulan</label><input type="datetime-local" name="due_date" required min="{{ now()->format('Y-m-d\TH:i') }}" value="{{ old('due_date') }}" class="w-full rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"></div>
                            <div><label class="mb-1 block text-sm font-semibold text-slate-700">Nilai maksimal</label><input type="number" name="max_score" min="1" max="1000" value="{{ old('max_score', 100) }}" required class="w-full rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"></div>
                        </div>
                        <button class="w-full rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700">+ Buat Tugas</button>
                    </form>
                </section>
            </div>

            @if ($errors->any())
                <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700"><p class="font-semibold">Periksa kembali isian:</p><ul class="mt-1 list-inside list-disc">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
            @endif

            {{-- Daftar materi --}}
            <section class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4"><div><h2 class="font-bold text-slate-800">Materi Pembelajaran</h2><p class="text-xs text-slate-500">Materi yang tersedia di kelas ini</p></div><span class="rounded-full bg-sky-100 px-3 py-1 text-xs font-bold text-sky-700">{{ $class->materials->count() }} materi</span></div>
                @forelse ($class->materials->sortByDesc('uploaded_at') as $material)
    <div class="flex flex-col gap-3 border-b border-slate-100 px-6 py-4 last:border-0 sm:flex-row sm:items-center sm:justify-between">

        <div class="flex items-start gap-3">
            <span class="rounded-lg bg-sky-50 p-2 text-lg">📄</span>

            <div>
                <h3 class="font-semibold text-slate-800">
                    {{ $material->title }}
                </h3>

                @if ($material->description)
                    <p class="mt-1 whitespace-pre-line text-sm text-slate-500">
                        {{ $material->description }}
                    </p>
                @endif

                <p class="mt-1 text-xs text-slate-400">
                    {{ optional($material->uploaded_at)->format('d M Y, H:i') ?? $material->created_at?->format('d M Y, H:i') }}
                </p>

                @if ($material->file_path)
                    <a href="{{ asset('storage/' . $material->file_path) }}"
                       target="_blank"
                       class="mt-2 inline-block text-sm font-semibold text-sky-700 hover:underline">
                        Lihat / unduh file ↗
                    </a>
                @endif
            </div>
        </div>

        
    <form action="{{ route('guru.classes.materials.destroy', [
        'cst' => $cst->cst_id,
        'material' => $material->material_id
    ]) }}" method="POST"
        onsubmit="return confirm('Hapus materi ini?')">

        @csrf
        @method('DELETE')

        <button type="submit"
                class="rounded-lg border border-rose-200 px-3 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50">
            Hapus
        </button>
    </form>

    </div>

@empty
    <p class="p-8 text-center text-sm text-slate-400">
        Belum ada materi. Tambahkan materi melalui formulir di atas.
    </p>
@endforelse
            </section>

            {{-- Daftar tugas --}}
            <section class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4"><div><h2 class="font-bold text-slate-800">Daftar Tugas</h2><p class="text-xs text-slate-500">Pantau tugas dan pengumpulan siswa</p></div><span class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-bold text-indigo-700">{{ $class->assignments->count() }} tugas</span></div>
                @forelse ($class->assignments->sortByDesc('created_at') as $assignment)
                    <div class="flex flex-col gap-3 border-b border-slate-100 px-6 py-4 last:border-0 sm:flex-row sm:items-center sm:justify-between">
                        <div><h3 class="font-semibold text-slate-800">{{ $assignment->title }}</h3>@if($assignment->description)<p class="mt-1 text-sm text-slate-500">{{ $assignment->description }}</p>@endif<div class="mt-2 flex flex-wrap gap-3 text-xs text-slate-500"><span>Deadline: {{ $assignment->due_date?->format('d M Y, H:i') }}</span><span>Nilai maks: {{ $assignment->max_score }}</span><span>{{ $assignment->submissions->count() }} pengumpulan</span></div></div>
                        <div class="flex items-center gap-2"><a href="{{ route('guru.classes.assignments.show', $assignment) }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-xs font-semibold text-white hover:bg-indigo-700">Lihat pengumpulan</a><form action="{{ route('guru.classes.assignments.destroy', $assignment) }}" method="POST" onsubmit="return confirm('Hapus tugas ini beserta data pengumpulannya?')">@csrf @method('DELETE')<button class="rounded-lg border border-rose-200 px-3 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50">Hapus</button></form></div>
                    </div>
                @empty
                    <p class="p-8 text-center text-sm text-slate-400">Belum ada tugas untuk kelas ini.</p>
                @endforelse
            </section>

            <div class="grid gap-6 lg:grid-cols-5">
                {{-- Ujian --}}
                <section class="lg:col-span-3 bg-white rounded-2xl shadow-sm border border-gray-100">
                    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                        <h2 class="font-semibold text-gray-900">Ujian</h2>
                        <span class="text-xs text-gray-500">{{ $exams->count() }} ujian</span>
                    </div>

                    @if ($exams->isEmpty())
                        <p class="p-8 text-center text-sm text-gray-500">Belum ada ujian untuk kelas ini.</p>
                    @else
                        <ul class="divide-y divide-gray-100">
                            @foreach ($exams as $e)
                                @php [$label, $cls] = $status($e); @endphp
                                <li class="p-5">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <h3 class="font-medium text-gray-900">{{ $e->title }}</h3>
                                            <p class="text-xs text-gray-500 mt-0.5 uppercase">{{ $e->exam_type }}</p>
                                        </div>
                                        <span class="shrink-0 text-xs font-semibold px-2.5 py-1 rounded-full {{ $cls }}">{{ $label }}</span>
                                    </div>
                                    <dl class="mt-3 grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                                        <div><dt class="text-gray-500">Soal</dt><dd class="font-semibold text-gray-900">{{ $e->questions_count }}</dd></div>
                                        <div><dt class="text-gray-500">Durasi</dt><dd class="font-semibold text-gray-900">{{ $e->duration_minutes }} menit</dd></div>
                                        <div><dt class="text-gray-500">Dibuka</dt><dd class="font-semibold text-gray-900">{{ \Illuminate\Support\Carbon::parse($e->start_time)->format('d M Y, H:i') }}</dd></div>
                                        <div><dt class="text-gray-500">Ditutup</dt><dd class="font-semibold text-gray-900">{{ \Illuminate\Support\Carbon::parse($e->end_time)->format('d M Y, H:i') }}</dd></div>
                                    </dl>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </section>

                {{-- Siswa --}}
                <section class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100">
                    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                        <h2 class="font-semibold text-gray-900">Siswa Bergabung</h2>
                        <span class="text-xs text-gray-500">{{ $students->count() }} siswa</span>
                    </div>

                    @if ($students->isEmpty())
                        <p class="p-8 text-center text-sm text-gray-500">
                            Belum ada siswa yang bergabung. Bagikan kode
                            <span class="font-mono font-semibold">{{ $class->join_code }}</span> ke siswa.
                        </p>
                    @else
                        <ol class="divide-y divide-gray-100 max-h-[32rem] overflow-y-auto">
                            @foreach ($students as $i => $s)
                                <li class="px-5 py-3 flex items-center gap-3 text-sm">
                                    <span class="w-6 text-right text-gray-400">{{ $i + 1 }}</span>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $s->user->name ?? $s->name ?? 'Siswa' }}</div>
                                        <div class="text-xs text-gray-500">NIS {{ $s->nis }}</div>
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                    @endif
                </section>
            </div>
        </div>
    </div>
</x-app-layout>