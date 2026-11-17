
<x-app-layout>
    @php
        $now = now();

        $status = function ($e) use ($now) {
            if (! $e->is_published) {
                return ['Draf', 'bg-slate-100 text-slate-600', ''];
            }

            $start = \Illuminate\Support\Carbon::parse($e->start_time);
            $end = \Illuminate\Support\Carbon::parse($e->end_time);

            if ($now->lt($start)) {
                return ['Belum dibuka', 'bg-blue-50 text-blue-700', ''];
            }

            if ($now->gt($end)) {
                return ['Ditutup', 'bg-slate-100 text-slate-600', ''];
            }

            return ['Berlangsung', 'bg-emerald-50 text-emerald-700', ''];
        };

        $formatOptions = [
            'pdf' => 'PDF',
            'doc' => 'DOC',
            'docx' => 'DOCX',
            'ppt' => 'PPT',
            'pptx' => 'PPTX',
            'xls' => 'XLS',
            'xlsx' => 'XLSX',
            'jpg' => 'JPG / JPEG',
            'png' => 'PNG',
            'zip' => 'ZIP',
            'txt' => 'TXT',
        ];

        $oldFormats = old('allowed_formats', ['pdf']);

        $materialCount = $class->materials->count();
        $assignmentCount = $class->assignments->count();
        $examCount = $exams->count();
        $studentCount = $students->count();

        $subjectName = $class->subject->name ?? $class->subject_code;
        $className = $class->schoolClass->name ?? 'Kelas '.$class->class_id;
    @endphp

    {{-- QUILL STYLE --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap');

        .lms-page {
            font-family: 'DM Sans', sans-serif;
        }

        .lms-shadow {
            box-shadow: 0 8px 30px rgba(15, 53, 100, .055);
        }

        .lms-card {
            border: 1px solid #e8eef6;
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(15, 53, 100, .045);
        }

        .lms-input {
            width: 100%;
            border: 1px solid #dce5f0;
            border-radius: 12px;
            padding: 11px 13px;
            font-size: 13px;
            color: #1e293b;
            transition: .2s;
        }

        .lms-input:focus {
            border-color: #3b82f6;
            outline: none;
            box-shadow: 0 0 0 3px rgba(59,130,246,.1);
        }

        .lms-label {
            display: block;
            margin-bottom: 7px;
            color: #334155;
            font-size: 12px;
            font-weight: 700;
        }

        .lms-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border-radius: 11px;
            padding: 11px 16px;
            font-size: 12px;
            font-weight: 700;
            transition: .2s;
        }

        .lms-btn-primary {
            background: #2563eb;
            color: white;
        }

        .lms-btn-primary:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
        }

        .lms-btn-light {
            background: #eff6ff;
            color: #1d4ed8;
        }

        .lms-btn-light:hover {
            background: #dbeafe;
        }

        .lms-section-title {
            color: #0f172a;
            font-size: 16px;
            font-weight: 800;
            letter-spacing: -.35px;
        }

        .lms-muted {
            color: #8492a6;
        }

        .rich {
            font-size: 13px;
            line-height: 1.75;
            color: #64748b;
            overflow-wrap: anywhere;
        }

        .rich ul {
            list-style: disc;
            padding-left: 1.25rem;
        }

        .rich ol {
            list-style: decimal;
            padding-left: 1.25rem;
        }

        .rich h2 {
            font-size: 1.05rem;
            font-weight: 700;
            color: #334155;
            margin-top: .5rem;
        }

        .rich h3 {
            font-size: .95rem;
            font-weight: 700;
            color: #334155;
            margin-top: .5rem;
        }

        .rich a {
            color: #0369a1;
            text-decoration: underline;
        }

        .rich img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 12px 0;
            border-radius: 12px;
        }

        .ql-editor {
            min-height: 145px;
            font-size: 13px;
            line-height: 1.7;
        }

        .ql-container {
            border-bottom-left-radius: 12px;
            border-bottom-right-radius: 12px;
            border-color: #dce5f0 !important;
        }

        .ql-toolbar {
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            border-color: #dce5f0 !important;
            background: #f8fafc;
        }

        .ql-editor img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }

        .lms-stat {
            transition: .2s;
        }

        .lms-stat:hover {
            transform: translateY(-3px);
        }

        .lms-item {
            transition: background .2s;
        }

        .lms-item:hover {
            background: #f8fbff;
        }

        .lms-modal {
            backdrop-filter: blur(5px);
        }

        .lms-scroll::-webkit-scrollbar {
            width: 5px;
        }

        .lms-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        @media(max-width: 640px) {
            .lms-hero {
                padding: 22px !important;
            }

            .lms-hero h1 {
                font-size: 25px !important;
            }
        }
    </style>

    <div class="lms-page min-h-screen bg-[#f5f8fc] py-7 sm:py-9">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">

            {{-- BREADCRUMB --}}
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-2 text-xs font-medium text-slate-400">
                    <a href="{{ route('guru.classes.index') }}"
                       class="transition hover:text-blue-600">
                        E-Class
                    </a>
                    <span>/</span>
                    <span class="font-bold text-blue-700">{{ $className }}</span>
                </div>

                <a href="{{ route('guru.classes.index') }}"
                   class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">
                    <span>←</span>
                    Kembali ke daftar kelas
                </a>
            </div>

            {{-- HERO --}}
            <section class="lms-hero relative overflow-hidden rounded-[25px] bg-gradient-to-br from-[#123d80] via-[#1756a8] to-[#2879df] p-7 text-white shadow-xl sm:p-9">

                <div class="pointer-events-none absolute -right-16 -top-28 h-80 w-80 rounded-full border-[35px] border-white/5"></div>
                <div class="pointer-events-none absolute -bottom-36 right-52 h-72 w-72 rounded-full bg-blue-400/10 blur-2xl"></div>

                <div class="relative z-10">
                    <div class="mb-5 flex flex-wrap items-center gap-2">
                        <span class="rounded-full border border-white/20 bg-white/10 px-3 py-1.5 text-[10px] font-bold uppercase tracking-[1.5px] text-blue-100">
                            TEACHER LEARNING SPACE
                        </span>
                        <span class="rounded-full bg-emerald-400/20 px-3 py-1.5 text-[10px] font-bold text-emerald-100">
                            ● KELAS AKTIF
                        </span>
                    </div>

                    <div class="max-w-3xl">
                        <p class="mb-2 text-sm font-medium text-blue-100">
                            {{ $className }} · {{ $class->academic_year }}
                        </p>

                        <h1 class="text-3xl font-extrabold tracking-tight sm:text-4xl">
                            {{ $subjectName }}
                        </h1>

                        <p class="mt-3 max-w-2xl text-sm leading-7 text-blue-100">
                            Kelola pembelajaran, bagikan materi, berikan tugas,
                            dan pantau perkembangan siswa dalam satu ruang kelas.
                        </p>
                    </div>

                    <div class="mt-7 flex flex-wrap gap-3">
                        <div class="rounded-xl border border-white/15 bg-white/10 px-4 py-3">
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-blue-200">
                                Tahun ajaran
                            </p>
                            <p class="mt-1 text-sm font-bold">
                                {{ $class->academic_year }}
                            </p>
                        </div>

                        <div class="rounded-xl border border-white/15 bg-white/10 px-4 py-3">
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-blue-200">
                                Semester
                            </p>
                            <p class="mt-1 text-sm font-bold">
                                {{ ucfirst($class->semester) }}
                            </p>
                        </div>

                        <div class="rounded-xl border border-white/15 bg-white/10 px-4 py-3">
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-blue-200">
                                Kode bergabung
                            </p>
                            <div class="mt-1 flex items-center gap-2">
                                <span id="join-code"
                                      class="font-mono text-sm font-extrabold tracking-[2px]">
                                    {{ $class->join_code }}
                                </span>
                                <button type="button"
                                        onclick="copyJoinCode()"
                                        class="rounded-md bg-white/15 px-2 py-1 text-[10px] font-bold transition hover:bg-white/25">
                                    Salin
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- STATISTIK --}}
            <section class="grid grid-cols-2 gap-4 lg:grid-cols-4">

                <div class="lms-card lms-stat p-5">
                    <div class="flex items-center justify-between">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-xl">
                            📚
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-blue-500">
                            Materi
                        </span>
                    </div>
                    <p class="mt-5 text-3xl font-extrabold text-slate-800">
                        {{ $materialCount }}
                    </p>
                    <p class="mt-1 text-xs text-slate-400">Materi pembelajaran</p>
                </div>

                <div class="lms-card lms-stat p-5">
                    <div class="flex items-center justify-between">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-50 text-xl">
                            📝
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-500">
                            Tugas
                        </span>
                    </div>
                    <p class="mt-5 text-3xl font-extrabold text-slate-800">
                        {{ $assignmentCount }}
                    </p>
                    <p class="mt-1 text-xs text-slate-400">Tugas diberikan</p>
                </div>

                <div class="lms-card lms-stat p-5">
                    <div class="flex items-center justify-between">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-violet-50 text-xl">
                            📋
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-violet-500">
                            Ujian
                        </span>
                    </div>
                    <p class="mt-5 text-3xl font-extrabold text-slate-800">
                        {{ $examCount }}
                    </p>
                    <p class="mt-1 text-xs text-slate-400">Ujian tersedia</p>
                </div>

                <div class="lms-card lms-stat p-5">
                    <div class="flex items-center justify-between">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-xl">
                            👨‍🎓
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-600">
                            Siswa
                        </span>
                    </div>
                    <p class="mt-5 text-3xl font-extrabold text-slate-800">
                        {{ $studentCount }}
                    </p>
                    <p class="mt-1 text-xs text-slate-400">Siswa bergabung</p>
                </div>

            </section>

            {{-- NOTIFIKASI ERROR --}}
            @if ($errors->any())
                <div class="rounded-2xl border border-rose-200 bg-rose-50 p-5 text-sm text-rose-700">
                    <div class="flex items-start gap-3">
                        <span class="text-xl">⚠️</span>
                        <div>
                            <p class="font-bold">Periksa kembali isian Anda</p>
                            <ul class="mt-2 list-inside list-disc space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            {{-- FORM MATERI DAN TUGAS --}}
            <div class="grid items-start gap-6 xl:grid-cols-2">

                {{-- TAMBAH MATERI --}}
                <section class="lms-card overflow-hidden">
                    <div class="border-b border-slate-100 bg-gradient-to-r from-blue-50 to-white px-6 py-5">
                        <div class="flex items-center gap-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-600 text-xl text-white shadow-lg shadow-blue-200">
                                📚
                            </div>
                            <div>
                                <h2 class="lms-section-title">Tambah Materi</h2>
                                <p class="mt-1 text-xs text-slate-400">
                                    Bagikan sumber belajar kepada siswa.
                                </p>
                            </div>
                        </div>
                    </div>

                    <form id="form-material-create"
                          action="{{ route('guru.classes.materials.store', $cst) }}"
                          method="POST"
                          enctype="multipart/form-data"
                          class="space-y-5 p-6">
                        @csrf

                        <div>
                            <label class="lms-label">Judul materi</label>
                            <input name="title"
                                   value="{{ old('title') }}"
                                   required
                                   maxlength="150"
                                   class="lms-input"
                                   placeholder="Contoh: Pengenalan Basis Data">
                        </div>

                        <div>
                            <label class="lms-label">Deskripsi materi</label>
                            <div id="editor-material-create"></div>
                            <input type="hidden"
                                   name="description"
                                   id="input-material-create">
                        </div>

                        <div>
                            <label class="lms-label">
                                Lampiran materi
                                <span class="font-normal text-slate-400">(maks. 20 MB)</span>
                            </label>

                            <div class="rounded-xl border-2 border-dashed border-blue-200 bg-blue-50/50 p-5 transition hover:border-blue-400">
                                <div class="text-center">
                                    <div class="text-2xl">☁️</div>
                                    <p class="mt-2 text-xs font-bold text-slate-700">
                                        Pilih file pembelajaran
                                    </p>
                                    <p class="mt-1 text-[11px] text-slate-400">
                                        PDF, Word, PowerPoint, Excel, gambar, video, ZIP
                                    </p>
                                    <input type="file"
                                           name="file"
                                           data-allowed="pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,jpeg,png,mp4,zip,txt"
                                           data-max-mb="20"
                                           accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.jpg,.jpeg,.png,.mp4,.zip,.txt"
                                           class="mt-4 block w-full text-xs text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-blue-600 file:px-4 file:py-2 file:text-xs file:font-bold file:text-white hover:file:bg-blue-700">
                                </div>
                            </div>
                        </div>

                        <button type="submit"
                                class="lms-btn lms-btn-primary w-full py-3">
                            <span>＋</span>
                            Unggah Materi
                        </button>
                    </form>
                </section>

                {{-- TAMBAH TUGAS --}}
                <section class="lms-card overflow-hidden">
                    <div class="border-b border-slate-100 bg-gradient-to-r from-indigo-50 to-white px-6 py-5">
                        <div class="flex items-center gap-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-600 text-xl text-white shadow-lg shadow-indigo-200">
                                📝
                            </div>
                            <div>
                                <h2 class="lms-section-title">Buat Tugas</h2>
                                <p class="mt-1 text-xs text-slate-400">
                                    Atur instruksi dan tenggat pengumpulan.
                                </p>
                            </div>
                        </div>
                    </div>

                    <form id="form-assignment-create"
                          action="{{ route('guru.classes.assignments.store', $cst) }}"
                          method="POST"
                          class="space-y-5 p-6">
                        @csrf

                        <input type="hidden" name="max_score" value="100">

                        <div>
                            <label class="lms-label">Judul tugas</label>
                            <input name="title"
                                   value="{{ old('title') }}"
                                   required
                                   maxlength="150"
                                   class="lms-input"
                                   placeholder="Contoh: Latihan Pertemuan 1">
                        </div>

                        <div>
                            <label class="lms-label">Instruksi tugas</label>
                            <div id="editor-assignment-create"></div>
                            <input type="hidden"
                                   name="description"
                                   id="input-assignment-create">
                        </div>

                        <div>
                            <label class="lms-label">Batas pengumpulan</label>
                            <input type="datetime-local"
                                   name="due_date"
                                   required
                                   min="{{ now()->format('Y-m-d\TH:i') }}"
                                   value="{{ old('due_date') }}"
                                   class="lms-input">
                        </div>

                        <div>
                            <label class="lms-label">Format file yang diterima</label>
                            <p class="mb-3 text-[11px] leading-5 text-slate-400">
                                Pilih format file yang boleh dikumpulkan oleh siswa.
                            </p>

                            <div class="grid grid-cols-2 gap-2 sm:grid-cols-3"
                                 data-format-group>
                                @foreach ($formatOptions as $ext => $label)
                                    <label class="flex cursor-pointer items-center gap-2 rounded-xl border border-slate-200 px-3 py-3 text-xs font-semibold text-slate-600 transition hover:border-blue-300 hover:bg-blue-50">
                                        <input type="checkbox"
                                               name="allowed_formats[]"
                                               value="{{ $ext }}"
                                               @checked(in_array($ext, $oldFormats))
                                               class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                        {{ $label }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <button type="submit"
                                class="lms-btn w-full bg-indigo-600 py-3 text-white hover:bg-indigo-700">
                            <span>＋</span>
                            Buat Tugas
                        </button>
                    </form>
                </section>
            </div>

            {{-- DAFTAR MATERI --}}
            <section class="lms-card overflow-hidden">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-6 py-5">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xl">📚</span>
                            <h2 class="lms-section-title">Materi Pembelajaran</h2>
                        </div>
                        <p class="mt-1 text-xs text-slate-400">
                            Seluruh materi yang telah dibagikan kepada siswa.
                        </p>
                    </div>

                    <span class="rounded-full bg-blue-50 px-4 py-2 text-xs font-bold text-blue-700">
                        {{ $materialCount }} Materi
                    </span>
                </div>

                @forelse ($class->materials->sortByDesc('uploaded_at') as $material)
                    <div class="lms-item flex flex-col gap-4 border-b border-slate-100 px-6 py-5 last:border-0 sm:flex-row sm:items-start sm:justify-between">

                        <div class="flex min-w-0 gap-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-xl">
                                📄
                            </div>

                            <div class="min-w-0">
                                <h3 class="break-words text-sm font-extrabold text-slate-800">
                                    {{ $material->title }}
                                </h3>

                                @if ($material->description)
                                    <div class="rich mt-2">
                                        {!! $material->description !!}
                                    </div>
                                @endif

                                <p class="mt-2 text-[11px] text-slate-400">
                                    {{ optional($material->uploaded_at)->format('d M Y, H:i') ?? $material->created_at?->format('d M Y, H:i') }}
                                </p>

                                @if ($material->file_path)
                                    <a href="{{ asset('storage/' . $material->file_path) }}"
                                       target="_blank"
                                       rel="noopener"
                                       class="mt-3 inline-flex items-center gap-2 rounded-lg bg-blue-50 px-3 py-2 text-xs font-bold text-blue-700 transition hover:bg-blue-100">
                                        <span>↗</span>
                                        Lihat / Unduh File
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="flex shrink-0 items-center gap-2">
                           <button type="button"
                                    class="js-edit-material lms-btn lms-btn-light"
                                    data-action="{{ route('guru.classes.materials.update', ['cst' => $cst->cst_id, 'material' => $material->material_id]) }}"
                                    data-title="{{ $material->title }}"
                                    data-description="{{ $material->description }}">
                                Edit
                            </button>

                            <form action="{{ route('guru.classes.materials.destroy', ['cst' => $cst->cst_id, 'material' => $material->material_id]) }}"
                                  method="POST"
                                  onsubmit="return confirm('Hapus materi ini?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="lms-btn border border-rose-100 bg-rose-50 text-rose-600 hover:bg-rose-100">
                                    Hapus
                                </button>
                            </form>
                        </div>

                        {{-- Data untuk modal edit --}}
                        <button type="button"
                                class="js-edit-material hidden"
                                data-action="{{ route('guru.classes.materials.update', ['cst' => $cst->cst_id, 'material' => $material->material_id]) }}"
                                data-title="{{ $material->title }}"
                                data-description="{{ $material->description }}">
                        </button>
                    </div>
                @empty
                    <div class="px-6 py-14 text-center">
                        <div class="text-4xl">📚</div>
                        <h3 class="mt-4 text-sm font-bold text-slate-700">
                            Belum ada materi pembelajaran
                        </h3>
                        <p class="mt-1 text-xs text-slate-400">
                            Materi yang Anda tambahkan akan muncul di sini.
                        </p>
                    </div>
                @endforelse
            </section>

            {{-- DAFTAR TUGAS --}}
            <section class="lms-card overflow-hidden">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-6 py-5">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xl">📝</span>
                            <h2 class="lms-section-title">Daftar Tugas</h2>
                        </div>
                        <p class="mt-1 text-xs text-slate-400">
                            Pantau tugas dan pengumpulan siswa.
                        </p>
                    </div>

                    <span class="rounded-full bg-indigo-50 px-4 py-2 text-xs font-bold text-indigo-700">
                        {{ $assignmentCount }} Tugas
                    </span>
                </div>

                @forelse ($class->assignments->sortByDesc('created_at') as $assignment)
                    @php
                        $formats = $assignment->allowed_formats ?? [];
                    @endphp

                    <div class="lms-item flex flex-col gap-4 border-b border-slate-100 px-6 py-5 last:border-0 xl:flex-row xl:items-center xl:justify-between">
                        <div class="flex min-w-0 gap-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-xl">
                                📋
                            </div>

                            <div class="min-w-0">
                                <h3 class="break-words text-sm font-extrabold text-slate-800">
                                    {{ $assignment->title }}
                                </h3>

                                @if ($assignment->description)
                                    <div class="rich mt-2">
                                        {!! $assignment->description !!}
                                    </div>
                                @endif

                                <div class="mt-3 flex flex-wrap gap-2">
                                    <span class="rounded-lg bg-slate-100 px-3 py-1.5 text-[10px] font-semibold text-slate-600">
                                        🕒 {{ $assignment->due_date?->format('d M Y, H:i') }}
                                    </span>

                                    <span class="rounded-lg bg-blue-50 px-3 py-1.5 text-[10px] font-semibold text-blue-700">
                                        Format: {{ $formats ? strtoupper(implode(', ', $formats)) : 'Semua format' }}
                                    </span>

                                    <span class="rounded-lg bg-emerald-50 px-3 py-1.5 text-[10px] font-semibold text-emerald-700">
                                        {{ $assignment->submissions->count() }} Pengumpulan
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-2 xl:shrink-0">
                            <a href="{{ route('guru.classes.assignments.show', $assignment) }}"
                               class="lms-btn lms-btn-primary">
                                Lihat Pengumpulan →
                            </a>

                            <button type="button"
                                    class="js-edit-assignment lms-btn lms-btn-light"
                                    data-action="{{ route('guru.classes.assignments.update', $assignment) }}"
                                    data-title="{{ $assignment->title }}"
                                    data-description="{{ $assignment->description }}"
                                    data-due="{{ $assignment->due_date?->format('Y-m-d\TH:i') }}"
                                    data-formats="{{ json_encode($formats) }}">
                                Edit
                            </button>

                            <form action="{{ route('guru.classes.assignments.destroy', $assignment) }}"
                                  method="POST"
                                  onsubmit="return confirm('Hapus tugas ini beserta data pengumpulannya?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="lms-btn border border-rose-100 bg-rose-50 text-rose-600 hover:bg-rose-100">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-14 text-center">
                        <div class="text-4xl">📝</div>
                        <h3 class="mt-4 text-sm font-bold text-slate-700">
                            Belum ada tugas
                        </h3>
                        <p class="mt-1 text-xs text-slate-400">
                            Buat tugas pertama untuk kelas ini.
                        </p>
                    </div>
                @endforelse
            </section>

            {{-- UJIAN DAN SISWA --}}
            <div class="grid gap-6 xl:grid-cols-5">

                {{-- UJIAN --}}
                <section class="lms-card overflow-hidden xl:col-span-3">
                    <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-xl">📋</span>
                                <h2 class="lms-section-title">Ujian</h2>
                            </div>
                            <p class="mt-1 text-xs text-slate-400">
                                Informasi ujian pada kelas ini.
                            </p>
                        </div>

                        <span class="rounded-full bg-violet-50 px-4 py-2 text-xs font-bold text-violet-700">
                            {{ $examCount }} Ujian
                        </span>
                    </div>

                    @forelse ($exams as $e)
                        @php
                            [$label, $cls] = $status($e);
                        @endphp

                        <div class="border-b border-slate-100 p-6 last:border-0">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <h3 class="text-sm font-extrabold text-slate-800">
                                        {{ $e->title }}
                                    </h3>
                                    <p class="mt-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                        {{ $e->exam_type }}
                                    </p>
                                </div>

                                <span class="rounded-full px-3 py-1.5 text-[10px] font-bold {{ $cls }}">
                                    {{ $label }}
                                </span>
                            </div>

                            <div class="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-4">
                                <div class="rounded-xl bg-slate-50 p-3">
                                    <p class="text-[10px] text-slate-400">Jumlah soal</p>
                                    <p class="mt-1 text-sm font-extrabold text-slate-800">
                                        {{ $e->questions->count() }}
                                    </p>
                                </div>

                                <div class="rounded-xl bg-slate-50 p-3">
                                    <p class="text-[10px] text-slate-400">Durasi</p>
                                    <p class="mt-1 text-sm font-extrabold text-slate-800">
                                        {{ $e->duration_minutes }} menit
                                    </p>
                                </div>

                                <div class="rounded-xl bg-slate-50 p-3">
                                    <p class="text-[10px] text-slate-400">Dibuka</p>
                                    <p class="mt-1 text-xs font-bold text-slate-800">
                                        {{ \Illuminate\Support\Carbon::parse($e->start_time)->format('d M Y, H:i') }}
                                    </p>
                                </div>

                                <div class="rounded-xl bg-slate-50 p-3">
                                    <p class="text-[10px] text-slate-400">Ditutup</p>
                                    <p class="mt-1 text-xs font-bold text-slate-800">
                                        {{ \Illuminate\Support\Carbon::parse($e->end_time)->format('d M Y, H:i') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-14 text-center">
                            <div class="text-4xl">📋</div>
                            <h3 class="mt-4 text-sm font-bold text-slate-700">
                                Belum ada ujian
                            </h3>
                            <p class="mt-1 text-xs text-slate-400">
                                Ujian yang tersedia akan muncul di bagian ini.
                            </p>
                        </div>
                    @endforelse
                </section>

                {{-- SISWA --}}
                <section class="lms-card overflow-hidden xl:col-span-2">
                    <div class="flex items-center justify-between border-b border-slate-100 px-5 py-5">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-xl">👨‍🎓</span>
                                <h2 class="lms-section-title">Siswa Bergabung</h2>
                            </div>
                            <p class="mt-1 text-xs text-slate-400">
                                Daftar peserta kelas.
                            </p>
                        </div>

                        <span class="rounded-full bg-emerald-50 px-3 py-2 text-xs font-bold text-emerald-700">
                            {{ $studentCount }}
                        </span>
                    </div>

                    @if ($students->isEmpty())
                        <div class="px-5 py-14 text-center">
                            <div class="text-4xl">👨‍🎓</div>
                            <p class="mt-4 text-sm font-bold text-slate-700">
                                Belum ada siswa
                            </p>
                            <p class="mt-2 text-xs leading-6 text-slate-400">
                                Bagikan kode kelas kepada siswa agar mereka dapat bergabung.
                            </p>
                        </div>
                    @else
                        <ol class="lms-scroll max-h-[480px] divide-y divide-slate-100 overflow-y-auto">
                            @foreach ($students as $i => $s)
                                <li class="flex items-center gap-3 px-5 py-4 transition hover:bg-blue-50/50">
                                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-xs font-extrabold text-blue-700">
                                        {{ strtoupper(substr($s->user->name ?? $s->name ?? 'S', 0, 1)) }}
                                    </span>

                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-xs font-bold text-slate-800">
                                            {{ $s->user->name ?? $s->name ?? 'Siswa' }}
                                        </p>
                                        <p class="mt-1 text-[10px] text-slate-400">
                                            NIS {{ $s->nis }}
                                        </p>
                                    </div>

                                    <span class="text-[10px] font-semibold text-slate-300">
                                        {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                </li>
                            @endforeach
                        </ol>
                    @endif
                </section>
            </div>

            <div class="pb-4 text-center">
                <p class="text-[11px] text-slate-400">
                    Methodist Learning Space · Teacher Workspace
                </p>
            </div>

        </div>
    </div>

    {{-- MODAL EDIT MATERI --}}
    <div id="modal-material"
         class="lms-modal fixed inset-0 z-50 hidden items-start justify-center overflow-y-auto bg-slate-950/50 p-4">

        <div class="my-8 w-full max-w-2xl rounded-2xl bg-white p-6 shadow-2xl">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-extrabold text-slate-800">Edit Materi</h2>
                    <p class="mt-1 text-xs text-slate-400">Perbarui informasi materi pembelajaran.</p>
                </div>

                <button type="button"
                        data-close-modal
                        class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-100 text-xl text-slate-500 hover:bg-slate-200">
                    &times;
                </button>
            </div>

            <form id="form-material-edit"
                  method="POST"
                  enctype="multipart/form-data"
                  class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="lms-label">Judul materi</label>
                    <input name="title"
                           id="edit-material-title"
                           required
                           maxlength="150"
                           class="lms-input">
                </div>

                <div>
                    <label class="lms-label">Deskripsi</label>
                    <div id="editor-material-edit"></div>
                    <input type="hidden"
                           name="description"
                           id="input-material-edit">
                </div>

                <div>
                    <label class="lms-label">Ganti file</label>
                    <input type="file"
                           name="file"
                           data-allowed="pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,jpeg,png,mp4,zip"
                           data-max-mb="20"
                           accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.jpg,.jpeg,.png,.mp4,.zip"
                           class="block w-full rounded-xl border border-slate-200 p-3 text-xs">
                    <p class="mt-2 text-[10px] text-slate-400">
                        Kosongkan jika tidak ingin mengganti file.
                    </p>
                </div>

                <div class="flex justify-end gap-2 border-t border-slate-100 pt-4">
                    <button type="button"
                            data-close-modal
                            class="lms-btn border border-slate-200 text-slate-600 hover:bg-slate-50">
                        Batal
                    </button>

                    <button type="submit"
                            class="lms-btn lms-btn-primary">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT TUGAS --}}
    <div id="modal-assignment"
         class="lms-modal fixed inset-0 z-50 hidden items-start justify-center overflow-y-auto bg-slate-950/50 p-4">

        <div class="my-8 w-full max-w-2xl rounded-2xl bg-white p-6 shadow-2xl">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-extrabold text-slate-800">Edit Tugas</h2>
                    <p class="mt-1 text-xs text-slate-400">Perbarui instruksi dan batas pengumpulan.</p>
                </div>

                <button type="button"
                        data-close-modal
                        class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-100 text-xl text-slate-500 hover:bg-slate-200">
                    &times;
                </button>
            </div>

            <form id="form-assignment-edit"
                  method="POST"
                  class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="lms-label">Judul tugas</label>
                    <input name="title"
                           id="edit-assignment-title"
                           required
                           maxlength="150"
                           class="lms-input">
                </div>

                <div>
                    <label class="lms-label">Instruksi tugas</label>
                    <div id="editor-assignment-edit"></div>
                    <input type="hidden"
                           name="description"
                           id="input-assignment-edit">
                </div>

                <div>
                    <label class="lms-label">Batas pengumpulan</label>
                    <input type="datetime-local"
                           name="due_date"
                           id="edit-assignment-due"
                           required
                           class="lms-input">
                </div>

                <div>
                    <label class="lms-label">Format file yang diterima</label>
                    <div class="grid grid-cols-2 gap-2 sm:grid-cols-3"
                         data-format-group>
                        @foreach ($formatOptions as $ext => $label)
                            <label class="flex items-center gap-2 rounded-xl border border-slate-200 px-3 py-3 text-xs font-semibold text-slate-600">
                                <input type="checkbox"
                                       name="allowed_formats[]"
                                       value="{{ $ext }}"
                                       class="edit-fmt rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-end gap-2 border-t border-slate-100 pt-4">
                    <button type="button"
                            data-close-modal
                            class="lms-btn border border-slate-200 text-slate-600 hover:bg-slate-50">
                        Batal
                    </button>

                    <button type="submit"
                            class="lms-btn lms-btn-primary">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script src="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.min.js"></script>
    <script src="{{ asset('js/file-validator.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // COPY KODE KELAS
            window.copyJoinCode = function () {
                const code = document.getElementById('join-code').innerText.trim();

                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(code).then(() => {
                        alert('Kode kelas berhasil disalin!');
                    });
                } else {
                    const temp = document.createElement('textarea');
                    temp.value = code;
                    document.body.appendChild(temp);
                    temp.select();
                    document.execCommand('copy');
                    temp.remove();
                    alert('Kode kelas berhasil disalin!');
                }
            };

            // KONFIGURASI UPLOAD GAMBAR
            const uploadUrl = @json(route('guru.classes.editor-images.store', $cst));
            const csrfToken = @json(csrf_token());

            function uploadQuillImage(quill) {
                const fileInput = document.createElement('input');

                fileInput.type = 'file';
                fileInput.accept = 'image/jpeg,image/png,image/gif,image/webp';

                fileInput.click();

                fileInput.onchange = async function () {
                    const file = fileInput.files[0];

                    if (!file) return;

                    if (file.size > 5 * 1024 * 1024) {
                        alert('Ukuran gambar maksimal 5 MB.');
                        return;
                    }

                    const formData = new FormData();
                    formData.append('image', file);

                    try {
                        const response = await fetch(uploadUrl, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: formData
                        });

                        const result = await response.json();

                        if (!response.ok) {
                            throw new Error(result.message || 'Gagal mengunggah gambar.');
                        }

                        const range = quill.getSelection(true);

                        quill.insertEmbed(range.index, 'image', result.url, 'user');
                        quill.setSelection(range.index + 1);

                    } catch (error) {
                        console.error(error);
                        alert(error.message || 'Terjadi kesalahan saat upload gambar.');
                    }
                };
            }

            function makeEditor(selector) {
                const quill = new Quill(selector, {
                    theme: 'snow',
                    modules: {
                        toolbar: {
                            container: [
                                [{ header: [1, 2, 3, false] }],
                                ['bold', 'italic', 'underline'],
                                [{ list: 'ordered' }, { list: 'bullet' }],
                                ['link', 'image', 'blockquote'],
                                ['clean']
                            ],
                            handlers: {
                                image: function () {
                                    uploadQuillImage(quill);
                                }
                            }
                        }
                    }
                });

                return quill;
            }

            // SINKRONISASI EDITOR
            function bindSync(form, quill, input) {
                form.addEventListener('submit', () => {
                    const empty =
                        quill.getText().trim() === '' &&
                        !quill.root.querySelector('img');

                    input.value = empty ? '' : quill.root.innerHTML;
                });
            }

            // VALIDASI FORMAT
            function requireFormat(form) {
                form.addEventListener('submit', (event) => {
                    const checked = form.querySelector(
                        '[data-format-group] input:checked'
                    );

                    if (!checked) {
                        event.preventDefault();
                        alert('Pilih minimal satu format file untuk pengumpulan tugas.');
                    }
                });
            }

            // EDITOR CREATE
            const qMatCreate = makeEditor('#editor-material-create');
            const qAsgCreate = makeEditor('#editor-assignment-create');

            const formMatCreate = document.getElementById('form-material-create');
            const formAsgCreate = document.getElementById('form-assignment-create');

            const oldMat = @json(old('description'));

            if (oldMat && document.querySelector('#form-material-create [name=title]').value) {
                qMatCreate.clipboard.dangerouslyPasteHTML(oldMat);
            }

            bindSync(
                formMatCreate,
                qMatCreate,
                document.getElementById('input-material-create')
            );

            bindSync(
                formAsgCreate,
                qAsgCreate,
                document.getElementById('input-assignment-create')
            );

            requireFormat(formAsgCreate);

            // EDITOR MODAL
            const qMatEdit = makeEditor('#editor-material-edit');
            const qAsgEdit = makeEditor('#editor-assignment-edit');

            const formMatEdit = document.getElementById('form-material-edit');
            const formAsgEdit = document.getElementById('form-assignment-edit');

            bindSync(
                formMatEdit,
                qMatEdit,
                document.getElementById('input-material-edit')
            );

            bindSync(
                formAsgEdit,
                qAsgEdit,
                document.getElementById('input-assignment-edit')
            );

            requireFormat(formAsgEdit);

            // MODAL
            const open = (id) => {
                const modal = document.getElementById(id);
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            };

            const close = (modal) => {
                if (!modal) return;
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            };

            document.querySelectorAll('[data-close-modal]').forEach(button => {
                button.addEventListener('click', () => {
                    close(button.closest('[id^=modal-]'));
                });
            });

            document.addEventListener('keydown', event => {
                if (event.key === 'Escape') {
                    document.querySelectorAll('[id^=modal-]').forEach(close);
                }
            });

            // EDIT MATERI
            document.querySelectorAll('.js-edit-material').forEach(button => {
                button.addEventListener('click', () => {

                    if (!button.dataset.action) return;

                    formMatEdit.action = button.dataset.action;

                    document.getElementById('edit-material-title').value =
                        button.dataset.title || '';

                    qMatEdit.setContents([]);

                    qMatEdit.clipboard.dangerouslyPasteHTML(
                        button.dataset.description || ''
                    );

                    formMatEdit.querySelector('[type=file]').value = '';

                    open('modal-material');
                });
            });

            // EDIT TUGAS
            document.querySelectorAll('.js-edit-assignment').forEach(button => {
                button.addEventListener('click', () => {

                    formAsgEdit.action = button.dataset.action;

                    document.getElementById('edit-assignment-title').value =
                        button.dataset.title || '';

                    document.getElementById('edit-assignment-due').value =
                        button.dataset.due || '';

                    qAsgEdit.setContents([]);

                    qAsgEdit.clipboard.dangerouslyPasteHTML(
                        button.dataset.description || ''
                    );

                    let formats = [];

                    try {
                        formats = JSON.parse(button.dataset.formats || '[]');
                    } catch (error) {
                        console.error('Format tugas tidak valid:', error);
                    }

                    formAsgEdit.querySelectorAll('.edit-fmt').forEach(checkbox => {
                        checkbox.checked = formats.includes(checkbox.value);
                    });

                    open('modal-assignment');
                });
            });

        });
    </script>
</x-app-layout>
