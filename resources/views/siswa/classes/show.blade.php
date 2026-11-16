<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-3"><div class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-100 text-xl">📘</div><div><h2 class="text-lg font-bold text-slate-800">Ruang Kelas</h2><p class="text-xs text-slate-500">E-Class Siswa</p></div></div>
            <a href="{{ route('siswa.classes.index') }}" class="hidden rounded-xl px-3 py-2 text-sm font-semibold text-sky-700 transition hover:bg-sky-50 sm:inline-flex">← Semua kelas</a>
        </div>
    </x-slot>

    <style>
        .rich { overflow-wrap: anywhere; font-size: .9rem; line-height: 1.75; color: #475569; }
        .rich ul { list-style: disc; padding-left: 1.35rem; margin: .45rem 0; }
        .rich ol { list-style: decimal; padding-left: 1.35rem; margin: .45rem 0; }
        .rich h2 { font-size: 1.1rem; font-weight: 800; color: #1e293b; margin: .75rem 0 .35rem; }
        .rich h3 { font-size: 1rem; font-weight: 700; color: #334155; margin: .65rem 0 .3rem; }
        .rich a { color: #0369a1; text-decoration: underline; }
        .rich blockquote { border-left: 3px solid #7dd3fc; padding-left: .85rem; color: #64748b; font-style: italic; margin: .6rem 0; }
        .rich img { max-width: 100%; height: auto; border-radius: .75rem; }
    </style>

    <div class="min-h-screen bg-slate-50 pb-12">
        <div class="mx-auto max-w-7xl space-y-7 px-4 py-6 sm:px-6 lg:px-8 lg:py-9">
            <a href="{{ route('siswa.classes.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-sky-700 transition hover:gap-3 sm:hidden">← Kembali ke semua kelas</a>

            @if(session('success'))<div role="status" class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-800">✓ {{ session('success') }}</div>@endif
            @if(session('error'))<div role="alert" class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm font-medium text-rose-800">{{ session('error') }}</div>@endif
            @if($errors->any())<div role="alert" class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm font-medium text-rose-800">{{ $errors->first() }}</div>@endif

            {{-- CLASS HERO --}}
            <section class="relative isolate overflow-hidden rounded-[2rem] bg-gradient-to-br from-sky-700 via-sky-600 to-blue-600 p-6 text-white shadow-xl shadow-sky-100 sm:p-8 lg:p-10">
                <div class="pointer-events-none absolute -right-16 -top-24 h-64 w-64 rounded-full border-[30px] border-white/10"></div><div class="pointer-events-none absolute -bottom-24 right-1/3 h-56 w-56 rounded-full bg-cyan-300/10 blur-2xl"></div>
                <div class="relative max-w-3xl">
                    <div class="flex flex-wrap items-center gap-2"><span class="rounded-full border border-white/20 bg-white/10 px-3 py-1.5 text-[11px] font-bold uppercase tracking-[.15em] text-sky-50">{{ $cst->schoolClass->name ?? 'Ruang Kelas' }}</span><span class="rounded-full bg-white/15 px-3 py-1.5 text-[11px] font-semibold text-white">{{ ucfirst($cst->semester ?? 'Semester') }}</span></div>
                    <p class="mt-5 text-xs font-bold uppercase tracking-[.2em] text-sky-100">MATA PELAJARAN</p>
                    <h1 class="mt-1 text-3xl font-extrabold leading-tight tracking-tight sm:text-4xl">{{ $cst->subject->name ?? $cst->subject_code }}</h1>
                    <p class="mt-3 flex flex-wrap items-center gap-x-2 gap-y-1 text-sm text-sky-100"><span>👨‍🏫</span><span>Pengajar:</span><strong class="font-bold text-white">{{ $cst->teacher->user->name ?? 'Guru' }}</strong></p>
                    <p class="mt-1 text-sm text-sky-100">Tahun ajaran {{ $cst->academic_year ?? '-' }}</p>
                    <div class="mt-6 flex flex-wrap gap-2"><a href="#materi" class="rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-sky-700 shadow-sm transition hover:-translate-y-0.5 hover:bg-sky-50">📚 Materi</a><a href="#tugas" class="rounded-xl border border-white/30 bg-white/10 px-4 py-2.5 text-sm font-bold text-white backdrop-blur transition hover:bg-white/20">📝 Tugas</a><a href="#ujian" class="rounded-xl border border-white/30 bg-white/10 px-4 py-2.5 text-sm font-bold text-white backdrop-blur transition hover:bg-white/20">✦ Ujian</a></div>
                </div>
            </section>

            {{-- QUICK COUNTS --}}
            <section class="grid grid-cols-3 gap-3 sm:gap-4">
                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:flex sm:items-center sm:gap-3 sm:p-5"><div class="text-xl sm:flex sm:h-11 sm:w-11 sm:items-center sm:justify-center sm:rounded-xl sm:bg-sky-50 sm:text-2xl">📚</div><div class="mt-2 sm:mt-0"><p class="text-xl font-extrabold text-sky-700">{{ $cst->materials->count() }}</p><p class="text-[11px] font-semibold text-slate-500 sm:text-xs">Materi</p></div></div>
                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:flex sm:items-center sm:gap-3 sm:p-5"><div class="text-xl sm:flex sm:h-11 sm:w-11 sm:items-center sm:justify-center sm:rounded-xl sm:bg-blue-50 sm:text-2xl">📝</div><div class="mt-2 sm:mt-0"><p class="text-xl font-extrabold text-blue-700">{{ $cst->assignments->count() }}</p><p class="text-[11px] font-semibold text-slate-500 sm:text-xs">Tugas</p></div></div>
                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:flex sm:items-center sm:gap-3 sm:p-5"><div class="text-xl sm:flex sm:h-11 sm:w-11 sm:items-center sm:justify-center sm:rounded-xl sm:bg-indigo-50 sm:text-2xl">✦</div><div class="mt-2 sm:mt-0"><p class="text-xl font-extrabold text-indigo-700">{{ $exams->count() }}</p><p class="text-[11px] font-semibold text-slate-500 sm:text-xs">Ujian</p></div></div>
            </section>

            {{-- MATERIALS --}}
            <section id="materi" class="scroll-mt-24 overflow-hidden rounded-3xl border border-slate-200/80 bg-white shadow-sm shadow-slate-200/40">
                <div class="flex flex-col gap-2 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-7"><div class="flex items-center gap-3"><div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-sky-100 text-xl">📚</div><div><h2 class="text-lg font-extrabold text-slate-800">Materi Pembelajaran</h2><p class="text-xs text-slate-500">Bahan belajar yang dibagikan oleh guru.</p></div></div><span class="w-fit rounded-full bg-sky-50 px-3 py-1 text-xs font-bold text-sky-700">{{ $cst->materials->count() }} materi</span></div>
                <div class="divide-y divide-slate-100">
                    @forelse($cst->materials->sortByDesc('uploaded_at') as $material)
                        <article class="p-5 transition hover:bg-slate-50/70 sm:p-7">
                            <div class="flex items-start gap-3"><div class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-50 text-lg">📄</div><div class="min-w-0 flex-1"><h3 class="break-words font-bold text-slate-800">{{ $material->title }}</h3>@if($material->description)<div class="rich mt-2">{!! $material->description !!}</div>@endif
                                @if($material->file_path)<a href="{{ asset('storage/'.$material->file_path) }}" target="_blank" rel="noopener" class="mt-4 inline-flex max-w-full items-center gap-2 rounded-xl border border-sky-100 bg-sky-50 px-4 py-2.5 text-sm font-bold text-sky-700 transition hover:border-sky-200 hover:bg-sky-100"><span>⬇</span><span class="truncate">Buka / unduh materi</span><span>↗</span></a>@endif
                                <p class="mt-3 text-xs text-slate-400">Dibagikan {{ $material->uploaded_at?->format('d M Y, H:i') ?? '-' }}</p>
                            </div></div>
                        </article>
                    @empty
                        <div class="px-5 py-12 text-center"><div class="text-3xl">📚</div><p class="mt-3 font-bold text-slate-700">Belum ada materi</p><p class="mt-1 text-sm text-slate-400">Materi dari guru akan tampil di sini.</p></div>
                    @endforelse
                </div>
            </section>

            {{-- ASSIGNMENTS --}}
            <section id="tugas" class="scroll-mt-24 overflow-hidden rounded-3xl border border-slate-200/80 bg-white shadow-sm shadow-slate-200/40">
                <div class="flex flex-col gap-2 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-7"><div class="flex items-center gap-3"><div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-100 text-xl">📝</div><div><h2 class="text-lg font-extrabold text-slate-800">Tugas & Pengumpulan</h2><p class="text-xs text-slate-500">Perhatikan tenggat dan format file sebelum mengirim jawaban.</p></div></div><span class="w-fit rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">{{ $cst->assignments->count() }} tugas</span></div>
                <div class="divide-y divide-slate-100">
                    @forelse($cst->assignments->sortByDesc('created_at') as $assignment)
                        @php
                            $submission = $subs->get($assignment->assignment_id);
                            $expired = $assignment->due_date ? now()->gt($assignment->due_date) : false;
                            $formats = $assignment->allowed_formats ?: ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'jpg', 'png', 'zip'];
                            $accept = collect($formats)->flatMap(fn ($f) => $f === 'jpg' ? ['.jpg', '.jpeg'] : ['.'.$f])->implode(',');
                        @endphp
                        <article class="p-5 sm:p-7">
                            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                                <div class="min-w-0 flex-1"><div class="flex flex-wrap items-center gap-2"><h3 class="break-words text-base font-extrabold text-slate-800">{{ $assignment->title }}</h3>
                                    @if($submission)<span class="rounded-full bg-emerald-50 px-3 py-1 text-[11px] font-bold text-emerald-700">✓ Terkumpul</span>@elseif($expired)<span class="rounded-full bg-rose-50 px-3 py-1 text-[11px] font-bold text-rose-700">Deadline lewat</span>@else<span class="rounded-full bg-amber-50 px-3 py-1 text-[11px] font-bold text-amber-700">Belum dikumpulkan</span>@endif
                                </div>
                                @if($assignment->description)<div class="rich mt-2">{!! $assignment->description !!}</div>@endif
                                <div class="mt-3 flex flex-wrap gap-x-4 gap-y-2 text-xs text-slate-500"><span class="inline-flex items-center gap-1.5">🗓 <strong class="font-semibold text-slate-600">Deadline:</strong> {{ $assignment->due_date?->format('d M Y, H:i') ?? 'Tidak ditentukan' }}</span><span class="inline-flex items-center gap-1.5">📎 Format: {{ strtoupper(implode(', ', $formats)) }}</span></div>
                                </div>
                            </div>
                            @if($submission)
                                <div class="mt-5 rounded-2xl border border-emerald-100 bg-emerald-50/70 p-4 sm:p-5"><div class="flex flex-wrap items-center justify-between gap-2"><p class="text-sm font-extrabold text-emerald-800">Jawaban berhasil dikirim</p><span class="text-xs font-medium text-emerald-700">{{ $submission->submitted_at?->format('d M Y, H:i') ?? '-' }}</span></div>
                                    @if($submission->file_path)<p class="mt-2 break-all text-xs text-emerald-700">File: {{ basename($submission->file_path) }}</p>@endif
                                    @if($submission->score !== null)<div class="mt-4 flex items-center gap-3"><div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white text-lg font-extrabold text-emerald-700 shadow-sm">{{ rtrim(rtrim(number_format((float)$submission->score, 2, '.', ''), '0'), '.') }}</div><div><p class="text-xs font-semibold uppercase tracking-wide text-emerald-700">Nilai tugas</p><p class="text-sm font-bold text-emerald-900">{{ $submission->score }} / {{ $assignment->max_score }}</p></div></div>@else<p class="mt-3 text-xs text-emerald-700">Jawaban sedang menunggu pemeriksaan guru.</p>@endif
                                    @if($submission->feedback)<div class="mt-4 border-t border-emerald-200 pt-3"><p class="text-xs font-bold uppercase tracking-wide text-emerald-700">Feedback guru</p><p class="mt-1 text-sm leading-6 text-emerald-900">{{ $submission->feedback }}</p></div>@endif
                                </div>
                            @elseif(!$expired)
                                <form action="{{ route('siswa.classes.assignments.submit', $assignment) }}" method="POST" enctype="multipart/form-data" class="mt-5 rounded-2xl border border-sky-100 bg-sky-50/70 p-4 sm:p-5">
                                    @csrf
                                    <label for="assignment-file-{{ $assignment->assignment_id }}" class="mb-2 block text-sm font-bold text-slate-700">Unggah jawabanmu</label>
                                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center"><input id="assignment-file-{{ $assignment->assignment_id }}" type="file" name="file" required data-allowed="{{ implode(',', $formats) }}" data-max-mb="20" accept="{{ $accept }}" class="min-w-0 w-full rounded-xl border border-slate-200 bg-white p-2 text-xs text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-sky-100 file:px-3 file:py-2 file:text-xs file:font-bold file:text-sky-700 hover:file:bg-sky-200 sm:flex-1">
                                        <button type="submit" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-sky-600 px-5 py-3 text-sm font-bold text-white shadow-md shadow-sky-200 transition hover:-translate-y-0.5 hover:bg-sky-700 focus:outline-none focus:ring-4 focus:ring-sky-200">Kumpulkan tugas <span>↑</span></button></div>
                                    <p class="mt-2 text-[11px] text-slate-500">Format {{ strtoupper(implode(', ', $formats)) }} · Maksimal 20 MB.</p>
                                </form>
                            @else
                                <div class="mt-5 rounded-2xl bg-rose-50 p-4 text-sm text-rose-700">Waktu pengumpulan tugas ini telah berakhir.</div>
                            @endif
                        </article>
                    @empty
                        <div class="px-5 py-12 text-center"><div class="text-3xl">📝</div><p class="mt-3 font-bold text-slate-700">Belum ada tugas</p><p class="mt-1 text-sm text-slate-400">Tugas yang diberikan guru akan muncul di sini.</p></div>
                    @endforelse
                </div>
            </section>

            {{-- EXAMS --}}
            <section id="ujian" class="scroll-mt-24 overflow-hidden rounded-3xl border border-slate-200/80 bg-white shadow-sm shadow-slate-200/40">
                <div class="flex flex-col gap-2 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-7"><div class="flex items-center gap-3"><div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-indigo-100 text-xl">✦</div><div><h2 class="text-lg font-extrabold text-slate-800">Ujian & Kuis</h2><p class="text-xs text-slate-500">Daftar ujian yang telah dipublikasikan guru.</p></div></div><span class="w-fit rounded-full bg-indigo-50 px-3 py-1 text-xs font-bold text-indigo-700">{{ $exams->count() }} ujian</span></div>
                <div class="divide-y divide-slate-100">
                    @forelse($exams as $exam)
                        @php
                            $session = $sessions->get($exam->exam_id);
                            $start = \Illuminate\Support\Carbon::parse($exam->start_time);
                            $end = \Illuminate\Support\Carbon::parse($exam->end_time);
                            if ($session && $session->status !== 'berlangsung') { $statusLabel = 'Selesai'; $statusClass = 'bg-emerald-50 text-emerald-700'; }
                            elseif (now()->lt($start)) { $statusLabel = 'Belum dibuka'; $statusClass = 'bg-blue-50 text-blue-700'; }
                            elseif (now()->gt($end)) { $statusLabel = 'Ditutup'; $statusClass = 'bg-slate-100 text-slate-600'; }
                            elseif ($session) { $statusLabel = 'Sedang berlangsung'; $statusClass = 'bg-amber-50 text-amber-700'; }
                            else { $statusLabel = 'Tersedia'; $statusClass = 'bg-sky-50 text-sky-700'; }
                        @endphp
                        <article class="flex flex-col gap-4 p-5 sm:p-7 lg:flex-row lg:items-center lg:justify-between">
                            <div class="min-w-0"><div class="flex flex-wrap items-center gap-2"><h3 class="break-words font-extrabold text-slate-800">{{ $exam->title }}</h3><span class="rounded-full px-3 py-1 text-[11px] font-bold {{ $statusClass }}">{{ $statusLabel }}</span></div><div class="mt-3 flex flex-wrap gap-x-4 gap-y-2 text-xs text-slate-500"><span>{{ strtoupper($exam->exam_type) }}</span><span>{{ $exam->questions_count }} soal</span><span>Mulai {{ $start->format('d M Y, H:i') }}</span><span>Selesai {{ $end->format('d M Y, H:i') }}</span></div></div>
                            <a href="{{ route('siswa.exams.show', $exam) }}" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-3 text-sm font-bold text-white shadow-md shadow-indigo-100 transition hover:-translate-y-0.5 hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-100">Lihat ujian <span>→</span></a>
                        </article>
                    @empty
                        <div class="px-5 py-12 text-center"><div class="text-3xl">✦</div><p class="mt-3 font-bold text-slate-700">Belum ada ujian atau kuis</p><p class="mt-1 text-sm text-slate-400">Ujian yang dipublikasikan guru akan ditampilkan di bagian ini.</p></div>
                    @endforelse
                </div>
            </section>
            <div class="flex flex-col items-center justify-between gap-3 border-t border-slate-200 pt-5 sm:flex-row"><a href="{{ route('siswa.classes.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-sky-700 hover:text-sky-900">← Kembali ke daftar kelas</a><p class="text-xs text-slate-400">SMAS Methodist Banda Aceh · E-Class Student Portal</p></div>
        </div>
    </div>
    <script src="{{ asset('js/file-validator.js') }}"></script>
</x-app-layout>
