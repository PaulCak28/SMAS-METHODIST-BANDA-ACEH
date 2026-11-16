<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-slate-800">E-Class</h2></x-slot>

    {{-- Gaya untuk teks dari editor guru (sama seperti halaman guru) --}}
    <style>
        .rich { font-size: .875rem; color: #475569; }
        .rich ul { list-style: disc; padding-left: 1.25rem; }
        .rich ol { list-style: decimal; padding-left: 1.25rem; }
        .rich h2 { font-size: 1.05rem; font-weight: 700; color: #334155; margin-top: .5rem; }
        .rich h3 { font-size: .95rem; font-weight: 700; color: #334155; margin-top: .5rem; }
        .rich a { color: #0369a1; text-decoration: underline; }
        .rich blockquote { border-left: 3px solid #cbd5e1; padding-left: .75rem; font-style: italic; }
    </style>

    <div class="py-8"><div class="mx-auto max-w-6xl space-y-6 px-4 sm:px-6 lg:px-8">
        <a href="{{ route('siswa.classes.index') }}" class="text-sm font-medium text-sky-700 hover:underline">← Kembali ke kelas saya</a>
        @if(session('success'))<div class="rounded-xl bg-emerald-50 p-4 text-sm text-emerald-700">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="rounded-xl bg-rose-50 p-4 text-sm text-rose-700">{{ session('error') }}</div>@endif
        @if($errors->any())<div class="rounded-xl bg-rose-50 p-4 text-sm text-rose-700">{{ $errors->first() }}</div>@endif

        <div class="rounded-2xl bg-gradient-to-r from-sky-600 to-blue-500 p-6 text-white shadow-sm">
            <p class="text-sm text-sky-100">{{ $cst->schoolClass->name ?? 'Kelas' }}</p>
            <h1 class="mt-1 text-2xl font-bold">{{ $cst->subject->name ?? $cst->subject_code }}</h1>
            <p class="mt-2 text-sm text-sky-100">Guru: {{ $cst->teacher->user->name ?? 'Guru' }}</p>
        </div>

        {{-- Materi --}}
        <section class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-4"><h2 class="font-bold text-slate-800">Materi Pembelajaran</h2></div>
            @forelse($cst->materials->sortByDesc('uploaded_at') as $material)
                <div class="border-b border-slate-100 p-5 last:border-0">
                    <h3 class="font-semibold text-slate-800">{{ $material->title }}</h3>
                    @if($material->description)
                        <div class="rich mt-1">{!! $material->description !!}</div>
                    @endif
                    @if($material->file_path)
                        <a href="{{ asset('storage/'.$material->file_path) }}" target="_blank" class="mt-2 inline-block text-sm font-semibold text-sky-700 hover:underline">Buka / unduh materi ↗</a>
                    @endif
                    <p class="mt-1 text-xs text-slate-400">{{ $material->uploaded_at?->format('d M Y, H:i') }}</p>
                </div>
            @empty
                <p class="p-7 text-center text-sm text-slate-400">Belum ada materi yang dibagikan.</p>
            @endforelse
        </section>

        {{-- Tugas --}}
        <section class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-4"><h2 class="font-bold text-slate-800">Tugas</h2></div>
            @forelse($cst->assignments->sortByDesc('created_at') as $assignment)
                @php
                    $submission = $subs->get($assignment->assignment_id);
                    $expired = now()->gt($assignment->due_date);
                    // Tugas lama (sebelum fitur format) dianggap menerima format umum
                    $formats = $assignment->allowed_formats ?: ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'jpg', 'png', 'zip'];
                    $accept = collect($formats)->flatMap(fn ($f) => $f === 'jpg' ? ['.jpg', '.jpeg'] : ['.'.$f])->implode(',');
                @endphp
                <div class="border-b border-slate-100 p-5 last:border-0">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <h3 class="font-semibold text-slate-800">{{ $assignment->title }}</h3>
                            @if($assignment->description)
                                <div class="rich mt-1">{!! $assignment->description !!}</div>
                            @endif
                            <p class="mt-2 text-xs text-slate-500">
                                Deadline: {{ $assignment->due_date?->format('d M Y, H:i') }} · Format file: {{ strtoupper(implode(', ', $formats)) }}
                            </p>
                        </div>
                        @if($submission)
                            <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">Sudah dikumpulkan</span>
                        @elseif($expired)
                            <span class="rounded-full bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-700">Deadline lewat</span>
                        @else
                            <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">Belum dikumpulkan</span>
                        @endif
                    </div>

                    @if($submission)
                        <div class="mt-3 rounded-xl bg-slate-50 p-3 text-sm text-slate-600">
                            <p>Dikumpulkan: {{ $submission->submitted_at?->format('d M Y, H:i') }}</p>
                            @if($submission->score !== null)
                                <p class="mt-1 font-semibold text-slate-800">Nilai: {{ $submission->score }} / {{ $assignment->max_score }}</p>
                            @endif
                            @if($submission->feedback)
                                <p class="mt-1">Feedback guru: {{ $submission->feedback }}</p>
                            @endif
                        </div>
                    @elseif(!$expired)
                        <form action="{{ route('siswa.classes.assignments.submit', $assignment) }}" method="POST" enctype="multipart/form-data" class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-end">
                            @csrf
                            <div class="flex-1">
                                <label class="mb-1 block text-xs font-semibold text-slate-600">Unggah jawaban ({{ strtoupper(implode(', ', $formats)) }}, maks. 20 MB)</label>
                                <input type="file" name="file" required
                                       data-allowed="{{ implode(',', $formats) }}" data-max-mb="20"
                                       accept="{{ $accept }}"
                                       class="block w-full rounded-lg border border-slate-300 p-2 text-sm">
                            </div>
                            <button class="rounded-lg bg-sky-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-sky-700">Kumpulkan tugas</button>
                        </form>
                    @endif
                </div>
            @empty
                <p class="p-7 text-center text-sm text-slate-400">Belum ada tugas dari guru.</p>
            @endforelse
        </section>
    </div></div>

    <script src="{{ asset('js/file-validator.js') }}"></script>
</x-app-layout>