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

        // Format file yang bisa dipilih guru untuk pengumpulan tugas
        $formatOptions = [
            'pdf' => 'PDF', 'doc' => 'DOC', 'docx' => 'DOCX',
            'ppt' => 'PPT', 'pptx' => 'PPTX', 'xls' => 'XLS', 'xlsx' => 'XLSX',
            'jpg' => 'JPG / JPEG', 'png' => 'PNG', 'zip' => 'ZIP', 'txt' => 'TXT',
        ];
        $oldFormats = old('allowed_formats', ['pdf']);
    @endphp

    {{-- Editor WYSIWYG (Quill) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css">
    <style>
        .ql-editor { min-height: 130px; font-size: .875rem; }
        .ql-container { border-bottom-left-radius: .75rem; border-bottom-right-radius: .75rem; }
        .ql-toolbar { border-top-left-radius: .75rem; border-top-right-radius: .75rem; }
        /* Tampilan hasil teks kaya (Tailwind preflight menghapus gaya list/heading) */
        .rich { font-size: .875rem; color: #64748b; }
        .rich ul { list-style: disc; padding-left: 1.25rem; }
        .rich ol { list-style: decimal; padding-left: 1.25rem; }
        .rich h2 { font-size: 1.05rem; font-weight: 700; color: #334155; margin-top: .5rem; }
        .rich h3 { font-size: .95rem; font-weight: 700; color: #334155; margin-top: .5rem; }
        .rich a { color: #0369a1; text-decoration: underline; }
        .rich blockquote { border-left: 3px solid #cbd5e1; padding-left: .75rem; font-style: italic; }
    </style>

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
                    <form id="form-material-create" action="{{ route('guru.classes.materials.store', $cst) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div><label class="mb-1 block text-sm font-semibold text-slate-700">Judul materi</label><input name="title" value="{{ old('title') }}" required maxlength="150" class="w-full rounded-xl border-slate-300 text-sm focus:border-sky-500 focus:ring-sky-500" placeholder="Contoh: Pengenalan Basis Data"></div>
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-slate-700">Deskripsi</label>
                            <div id="editor-material-create"></div>
                            <input type="hidden" name="description" id="input-material-create">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-slate-700">File materi <span class="font-normal text-slate-400">(opsional, maks. 20 MB)</span></label>
                            <input type="file" name="file"
                                   data-allowed="pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,png,mp4,zip" data-max-mb="20"
                                   accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.jpg,.jpeg,.png,.mp4,.zip"
                                   class="block w-full rounded-xl border border-slate-300 p-2 text-sm">
                        </div>
                        <button class="w-full rounded-xl bg-sky-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-sky-700">+ Unggah Materi</button>
                    </form>
                </section>

                <section class="rounded-2xl border border-indigo-100 bg-white p-6 shadow-sm">
                    <div class="mb-4 flex items-center gap-3">
                        <span class="rounded-xl bg-indigo-100 p-3 text-xl">📝</span>
                        <div><h2 class="text-lg font-bold text-slate-800">Buat Tugas</h2><p class="text-sm text-slate-500">Buat tugas dan tentukan batas pengumpulan.</p></div>
                    </div>
                    <form id="form-assignment-create" action="{{ route('guru.classes.assignments.store', $cst) }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="max_score" value="100">
                        <div><label class="mb-1 block text-sm font-semibold text-slate-700">Judul tugas</label><input name="title" value="{{ old('title') }}" required maxlength="150" class="w-full rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Contoh: Latihan Pertemuan 1"></div>
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-slate-700">Instruksi tugas</label>
                            <div id="editor-assignment-create"></div>
                            <input type="hidden" name="description" id="input-assignment-create">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-slate-700">Batas pengumpulan</label>
                            <input type="datetime-local" name="due_date" required min="{{ now()->format('Y-m-d\TH:i') }}" value="{{ old('due_date') }}" class="w-full rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-slate-700">Upload tugas: format file yang diterima</label>
                            <p class="mb-2 text-xs text-slate-500">Siswa hanya bisa mengunggah file dengan format yang Anda centang. Isi file ikut diperiksa, bukan hanya namanya.</p>
                            <div class="grid grid-cols-2 gap-2 sm:grid-cols-3" data-format-group>
                                @foreach ($formatOptions as $ext => $label)
                                    <label class="flex items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-xs font-medium text-slate-700">
                                        <input type="checkbox" name="allowed_formats[]" value="{{ $ext }}" @checked(in_array($ext, $oldFormats)) class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                        {{ $label }}
                                    </label>
                                @endforeach
                            </div>
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
                                <h3 class="font-semibold text-slate-800">{{ $material->title }}</h3>

                                @if ($material->description)
                                    <div class="rich mt-1">{!! $material->description !!}</div>
                                @endif

                                <p class="mt-1 text-xs text-slate-400">
                                    {{ optional($material->uploaded_at)->format('d M Y, H:i') ?? $material->created_at?->format('d M Y, H:i') }}
                                </p>

                                @if ($material->file_path)
                                    <a href="{{ asset('storage/' . $material->file_path) }}" target="_blank" class="mt-2 inline-block text-sm font-semibold text-sky-700 hover:underline">
                                        Lihat / unduh file ↗
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <button type="button"
                                    class="js-edit-material rounded-lg border border-sky-200 px-3 py-2 text-xs font-semibold text-sky-700 hover:bg-sky-50"
                                    data-action="{{ route('guru.classes.materials.update', ['cst' => $cst->cst_id, 'material' => $material->material_id]) }}"
                                    data-title="{{ $material->title }}"
                                    data-description="{{ $material->description }}">
                                Edit
                            </button>

                            <form action="{{ route('guru.classes.materials.destroy', ['cst' => $cst->cst_id, 'material' => $material->material_id]) }}" method="POST" onsubmit="return confirm('Hapus materi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-lg border border-rose-200 px-3 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50">Hapus</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="p-8 text-center text-sm text-slate-400">Belum ada materi. Tambahkan materi melalui formulir di atas.</p>
                @endforelse
            </section>

            {{-- Daftar tugas --}}
            <section class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4"><div><h2 class="font-bold text-slate-800">Daftar Tugas</h2><p class="text-xs text-slate-500">Pantau tugas dan pengumpulan siswa</p></div><span class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-bold text-indigo-700">{{ $class->assignments->count() }} tugas</span></div>
                @forelse ($class->assignments->sortByDesc('created_at') as $assignment)
                    @php $formats = $assignment->allowed_formats ?? []; @endphp
                    <div class="flex flex-col gap-3 border-b border-slate-100 px-6 py-4 last:border-0 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="font-semibold text-slate-800">{{ $assignment->title }}</h3>
                            @if ($assignment->description)
                                <div class="rich mt-1">{!! $assignment->description !!}</div>
                            @endif
                            <div class="mt-2 flex flex-wrap gap-3 text-xs text-slate-500">
                                <span>Deadline: {{ $assignment->due_date?->format('d M Y, H:i') }}</span>
                                <span>Format: {{ $formats ? strtoupper(implode(', ', $formats)) : 'Semua format' }}</span>
                                <span>{{ $assignment->submissions->count() }} pengumpulan</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('guru.classes.assignments.show', $assignment) }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-xs font-semibold text-white hover:bg-indigo-700">Lihat pengumpulan</a>
                            <button type="button"
                                    class="js-edit-assignment rounded-lg border border-indigo-200 px-3 py-2 text-xs font-semibold text-indigo-700 hover:bg-indigo-50"
                                    data-action="{{ route('guru.classes.assignments.update', $assignment) }}"
                                    data-title="{{ $assignment->title }}"
                                    data-description="{{ $assignment->description }}"
                                    data-due="{{ $assignment->due_date?->format('Y-m-d\TH:i') }}"
                                    data-formats="{{ json_encode($formats) }}">
                                Edit
                            </button>
                            <form action="{{ route('guru.classes.assignments.destroy', $assignment) }}" method="POST" onsubmit="return confirm('Hapus tugas ini beserta data pengumpulannya?')">
                                @csrf @method('DELETE')
                                <button class="rounded-lg border border-rose-200 px-3 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50">Hapus</button>
                            </form>
                        </div>
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

    {{-- ===== Modal Edit Materi ===== --}}
    <div id="modal-material" class="fixed inset-0 z-50 hidden items-start justify-center overflow-y-auto bg-slate-900/50 p-4">
        <div class="my-8 w-full max-w-2xl rounded-2xl bg-white p-6 shadow-xl">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-bold text-slate-800">Edit Materi</h2>
                <button type="button" data-close-modal class="text-2xl leading-none text-slate-400 hover:text-slate-600" aria-label="Tutup">&times;</button>
            </div>
            <form id="form-material-edit" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf @method('PUT')
                <div><label class="mb-1 block text-sm font-semibold text-slate-700">Judul materi</label><input name="title" id="edit-material-title" required maxlength="150" class="w-full rounded-xl border-slate-300 text-sm focus:border-sky-500 focus:ring-sky-500"></div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Deskripsi</label>
                    <div id="editor-material-edit"></div>
                    <input type="hidden" name="description" id="input-material-edit">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Ganti file <span class="font-normal text-slate-400">(kosongkan jika tidak diganti)</span></label>
                    <input type="file" name="file" data-allowed="pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,png,mp4,zip" data-max-mb="20" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.jpg,.jpeg,.png,.mp4,.zip" class="block w-full rounded-xl border border-slate-300 p-2 text-sm">
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" data-close-modal class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">Batal</button>
                    <button class="rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-700">Simpan perubahan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== Modal Edit Tugas ===== --}}
    <div id="modal-assignment" class="fixed inset-0 z-50 hidden items-start justify-center overflow-y-auto bg-slate-900/50 p-4">
        <div class="my-8 w-full max-w-2xl rounded-2xl bg-white p-6 shadow-xl">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-bold text-slate-800">Edit Tugas</h2>
                <button type="button" data-close-modal class="text-2xl leading-none text-slate-400 hover:text-slate-600" aria-label="Tutup">&times;</button>
            </div>
            <form id="form-assignment-edit" method="POST" class="space-y-4">
                @csrf @method('PUT')
                <div><label class="mb-1 block text-sm font-semibold text-slate-700">Judul tugas</label><input name="title" id="edit-assignment-title" required maxlength="150" class="w-full rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"></div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Instruksi tugas</label>
                    <div id="editor-assignment-edit"></div>
                    <input type="hidden" name="description" id="input-assignment-edit">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Batas pengumpulan</label>
                    <input type="datetime-local" name="due_date" id="edit-assignment-due" required class="w-full rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Upload tugas: format file yang diterima</label>
                    <div class="grid grid-cols-2 gap-2 sm:grid-cols-3" data-format-group>
                        @foreach ($formatOptions as $ext => $label)
                            <label class="flex items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-xs font-medium text-slate-700">
                                <input type="checkbox" name="allowed_formats[]" value="{{ $ext }}" class="edit-fmt rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" data-close-modal class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">Batal</button>
                    <button class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">Simpan perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.min.js"></script>
    <script src="{{ asset('js/file-validator.js') }}"></script>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const toolbar = [
            [{ header: [2, 3, false] }],
            ['bold', 'italic', 'underline'],
            [{ list: 'ordered' }, { list: 'bullet' }],
            ['link', 'blockquote'],
            ['clean'],
        ];
        const makeEditor = (sel, placeholder) =>
            new Quill(sel, { theme: 'snow', placeholder, modules: { toolbar } });

        // Salin isi editor ke input hidden saat form dikirim
        const bindSync = (form, quill, input) => {
            form.addEventListener('submit', () => {
                const empty = quill.getText().trim() === '' && !quill.root.querySelector('img');
                input.value = empty ? '' : quill.root.innerHTML;
            });
        };

        // Wajib pilih minimal satu format file
        const requireFormat = (form) => {
            form.addEventListener('submit', (e) => {
                if (!form.querySelector('[data-format-group] input:checked')) {
                    e.preventDefault();
                    alert('Pilih minimal satu format file untuk pengumpulan tugas.');
                }
            });
        };

        // --- Form tambah ---
        const qMatCreate = makeEditor('#editor-material-create', 'Tuliskan ringkasan materi...');
        const qAsgCreate = makeEditor('#editor-assignment-create', 'Jelaskan tugas yang harus dikerjakan siswa...');
        const oldMat = @json(old('description'));
        if (oldMat && document.querySelector('#form-material-create [name=title]').value) qMatCreate.clipboard.dangerouslyPasteHTML(oldMat);
        bindSync(document.getElementById('form-material-create'), qMatCreate, document.getElementById('input-material-create'));
        bindSync(document.getElementById('form-assignment-create'), qAsgCreate, document.getElementById('input-assignment-create'));
        requireFormat(document.getElementById('form-assignment-create'));

        // --- Modal edit ---
        const qMatEdit = makeEditor('#editor-material-edit', 'Tuliskan ringkasan materi...');
        const qAsgEdit = makeEditor('#editor-assignment-edit', 'Jelaskan tugas yang harus dikerjakan siswa...');
        const formMatEdit = document.getElementById('form-material-edit');
        const formAsgEdit = document.getElementById('form-assignment-edit');
        bindSync(formMatEdit, qMatEdit, document.getElementById('input-material-edit'));
        bindSync(formAsgEdit, qAsgEdit, document.getElementById('input-assignment-edit'));
        requireFormat(formAsgEdit);

        const open = (id) => { const m = document.getElementById(id); m.classList.remove('hidden'); m.classList.add('flex'); };
        const close = (m) => { m.classList.add('hidden'); m.classList.remove('flex'); };
        document.querySelectorAll('[data-close-modal]').forEach(b =>
            b.addEventListener('click', () => close(b.closest('[id^=modal-]'))));
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') document.querySelectorAll('[id^=modal-]').forEach(close);
        });

        document.querySelectorAll('.js-edit-material').forEach(btn => btn.addEventListener('click', () => {
            formMatEdit.action = btn.dataset.action;
            document.getElementById('edit-material-title').value = btn.dataset.title || '';
            qMatEdit.setContents([]);
            qMatEdit.clipboard.dangerouslyPasteHTML(btn.dataset.description || '');
            formMatEdit.querySelector('[type=file]').value = '';
            open('modal-material');
        }));

        document.querySelectorAll('.js-edit-assignment').forEach(btn => btn.addEventListener('click', () => {
            formAsgEdit.action = btn.dataset.action;
            document.getElementById('edit-assignment-title').value = btn.dataset.title || '';
            document.getElementById('edit-assignment-due').value = btn.dataset.due || '';
            qAsgEdit.setContents([]);
            qAsgEdit.clipboard.dangerouslyPasteHTML(btn.dataset.description || '');
            let formats = [];
            try { formats = JSON.parse(btn.dataset.formats || '[]'); } catch (e) {}
            formAsgEdit.querySelectorAll('.edit-fmt').forEach(cb => { cb.checked = formats.includes(cb.value); });
            open('modal-assignment');
        }));
    });
    </script>
</x-app-layout>