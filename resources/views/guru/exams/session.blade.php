<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[.2em] text-[#267BB5]">Methodist Learning Space</p>
                <h2 class="font-bold text-xl text-slate-800 leading-tight">
                    Lembar Jawaban: {{ $session->student->user->name ?? 'Anonim' }}
                </h2>
                <p class="text-sm text-slate-500 mt-0.5">
                    {{ $exam->title }} — {{ $exam->classSubjectTeacher?->subject?->name }}
                </p>
            </div>
            <a href="{{ route('guru.exams.results', $exam) }}" class="inline-flex items-center gap-2 rounded-full border border-[#E7EDF2] bg-white px-4 py-2 text-sm font-semibold text-[#123B60] shadow-sm transition hover:border-[#267BB5] hover:bg-[#E8F1F8]">
                <span aria-hidden="true">&larr;</span> Kembali ke Daftar Nilai
            </a>
        </div>
    </x-slot>

    @php
        $totalScore = min(100, (float) $answers->sum('score'));
        $isFinished = in_array($session->status, ['selesai', 'keluar'], true);
        $waktuSelesai = $session->finished_at ?? ($isFinished ? $session->updated_at : null);
        $durasiDetik = $session->started_at && $waktuSelesai
            ? max(0, (int) \Carbon\Carbon::parse($session->started_at)->diffInSeconds(\Carbon\Carbon::parse($waktuSelesai)))
            : null;
    @endphp

    <div class="min-h-screen bg-[#FAF9F6] py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">
            
            <!-- Alert Notifikasi -->
            @if (session('success'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
                     class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-sm font-medium text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-xl bg-rose-50 border border-rose-200 p-4 text-sm font-medium text-rose-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- ======================================================== -->
                <!-- KARTU INFO SISWA                                         -->
                <!-- ======================================================== -->
                <div class="md:col-span-2 bg-white p-6 shadow-sm ring-1 ring-slate-200 rounded-2xl flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">{{ $session->student->user->name ?? 'Anonim' }}</h3>
                        <p class="text-sm text-slate-500 font-medium">
                            NIS: {{ $session->student_id }} | NISN: {{ $session->student->nisn ?? '-' }}
                        </p>
                        
                        <div class="flex items-center gap-3 mt-3 text-xs font-semibold flex-wrap">
                            <span class="bg-slate-100 text-slate-600 px-2.5 py-1 rounded-lg">
                                Mulai: {{ $session->started_at ? \Carbon\Carbon::parse($session->started_at)->format('H:i:s') : '-' }}
                            </span>
                            <span class="bg-slate-100 text-slate-600 px-2.5 py-1 rounded-lg">
                                Selesai: {{ $waktuSelesai ? \Carbon\Carbon::parse($waktuSelesai)->format('H:i:s') : '-' }}
                            </span>
                            @if($durasiDetik !== null)
                                <span class="bg-slate-100 text-slate-600 px-2.5 py-1 rounded-lg">
                                    Durasi: {{ intdiv($durasiDetik, 60) }} menit {{ $durasiDetik % 60 }} detik
                                </span>
                            @endif
                            @if($session->status === 'selesai')
                                <span class="bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-lg uppercase">Selesai</span>
                            @elseif($session->status === 'keluar')
                                <span class="bg-rose-100 text-rose-700 px-2.5 py-1 rounded-lg uppercase">Dikeluarkan</span>
                            @else
                                <span class="bg-amber-100 text-amber-700 px-2.5 py-1 rounded-lg uppercase">Berlangsung</span>
                            @endif
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-bold text-slate-500 uppercase mb-1">Total Nilai Akhir</p>
                        <p class="text-4xl font-extrabold {{ $totalScore >= $exam->pass_score ? 'text-[#123B60]' : 'text-rose-600' }}">
                            {{ number_format($totalScore, 2) }}
                        </p>
                    </div>
                </div>

                <!-- ======================================================== -->
                <!-- KARTU LOG PELANGGARAN                                    -->
                <!-- ======================================================== -->
                <div class="bg-white p-6 shadow-sm ring-1 ring-slate-200 rounded-2xl">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-bold text-slate-800">Log Pelanggaran</h3>
                        <span class="text-xs font-bold px-2 py-0.5 rounded {{ $session->violations->count() > 0 ? 'bg-rose-100 text-rose-700' : 'bg-slate-100 text-slate-500' }}">
                            {{ $session->violations->count() }} Kali
                        </span>
                    </div>
                    
                    <div class="space-y-2 max-h-24 overflow-y-auto pr-1">
                        @forelse($session->violations as $v)
                            <div class="text-[11px] bg-rose-50 border border-rose-100 p-2 rounded-lg text-rose-700">
                                <span class="font-bold">
                                    {{ $v->occurred_at ? \Carbon\Carbon::parse($v->occurred_at)->format('H:i:s') : '-' }}
                                </span>
                                - {{ $v->detail ?? Str::headline($v->type ?? 'Pelanggaran ujian') }}
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 font-medium text-center py-2">Tidak ada catatan pelanggaran. Siswa ini aman.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- ======================================================== -->
            <!-- LEMBAR JAWABAN & FORM KOREKSI ESAI                       -->
            <!-- ======================================================== -->
            <form action="{{ route('guru.exams.grade-essay', $session->exam_session_id) }}" method="POST">
                @csrf
                @method('PATCH')
                
                @php
                    $essayCount = $exam->questions->where('question_type', 'esai')->count();
                @endphp

                <div class="bg-white shadow-sm ring-1 ring-slate-200 rounded-2xl overflow-hidden">
                    <div class="flex items-center justify-between p-6 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="text-lg font-bold text-slate-800">Detail Jawaban Siswa</h3>
                        @if($essayCount > 0)
                            <button type="submit" class="text-xs font-bold text-white bg-[#123B60] hover:bg-[#0B2B48] px-4 py-2 rounded-xl shadow-sm transition-colors">
                                Simpan Nilai Esai
                            </button>
                        @endif
                    </div>

                    <div class="p-6 space-y-6">
                        @forelse($exam->questions as $index => $q)
                            @php
                                $ans = $answers->get($q->exam_question_id);
                                $answerValue = $ans && is_array($ans->answer) ? reset($ans->answer) : ($ans->answer ?? null);
                                $displayAnswer = $answerValue;
                                if ($q->question_type === 'pilihan_ganda' && $answerValue !== null) {
                                    $optionValues = (array) $q->options;
                                    $optionKeys = array_keys($optionValues);
                                    $answerIndex = array_search((string) $answerValue, array_map('strval', $optionKeys), true);
                                    if ($answerIndex === false && is_numeric($answerValue)) {
                                        $answerIndex = (int) $answerValue;
                                    }
                                    if ($answerIndex === false && is_string($answerValue) && strlen($answerValue) === 1 && ctype_alpha($answerValue)) {
                                        $answerIndex = ord(strtoupper($answerValue)) - ord('A');
                                    }
                                    $displayAnswer = $answerIndex !== false && $answerIndex < count($optionKeys)
                                        ? chr(65 + $answerIndex).'. '.($optionValues[$optionKeys[$answerIndex]] ?? '')
                                        : $answerValue;
                                }
                            @endphp
                            
                            <div class="p-5 border {{ $q->question_type === 'esai' ? 'border-amber-200 bg-amber-50/30' : 'border-slate-200 bg-white' }} rounded-xl space-y-4">
                                
                                <!-- Header Soal -->
                                <div class="flex items-center justify-between gap-2 border-b border-slate-100 pb-3">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center justify-center rounded-lg bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700">
                                            Soal #{{ $index + 1 }}
                                        </span>
                                        <span class="inline-flex items-center rounded-lg bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-600">
                                            {{ Str::headline($q->question_type) }}
                                        </span>
                                    </div>
                                    
                                    <!-- Indikator Nilai -->
                                    @if($q->question_type !== 'esai')
                                        <div class="text-xs font-bold {{ ($ans->score ?? 0) > 0 ? 'text-emerald-600 bg-emerald-50' : 'text-rose-600 bg-rose-50' }} px-3 py-1 rounded-lg">
                                            {{ number_format((float) ($ans->score ?? 0), 2) }} / {{ number_format((float) $q->score_weight, 2) }} Poin
                                        </div>
                                    @else
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-bold text-amber-700">
                                                Beri Nilai (Maks {{ number_format((float) $q->score_weight, 2) }}):
                                            </span>
                                            <input type="number" step="0.01" min="0" max="{{ $q->score_weight }}"
                                                   name="scores[{{ $q->exam_question_id }}]" 
                                                   value="{{ $ans->score ?? 0 }}"
                                                   class="w-24 rounded-lg border-amber-300 py-1 text-xs font-bold text-center focus:ring-amber-500 focus:border-amber-500 bg-white shadow-sm">
                                        </div>
                                    @endif
                                </div>

                                <!-- Pertanyaan -->
                                <div class="prose prose-slate max-w-none text-sm text-slate-800">
                                    {!! $q->question_text !!}
                                </div>

                                <!-- Jawaban Siswa -->
                                <div class="mt-4 pt-4 border-t border-slate-100">
                                    <p class="text-[11px] font-bold text-slate-400 uppercase mb-2">Jawaban Siswa:</p>
                                    
                                    @if(!$ans || (blank($ans->answer) && blank($ans->answer_file)))
                                        <div class="text-xs font-semibold text-slate-400 italic bg-slate-50 p-3 rounded-lg border border-slate-100">
                                            Siswa tidak menjawab soal ini.
                                        </div>
                                    @else
                                        @if($q->question_type === 'esai')
                                            @if(!blank($ans->answer))
                                                <div class="text-sm text-slate-700 bg-white p-4 rounded-xl border border-slate-200 whitespace-pre-wrap leading-relaxed shadow-sm">
                                                    {{ is_array($ans->answer) ? json_encode($ans->answer) : $ans->answer }}
                                                </div>
                                            @endif
                                            @if(!blank($ans->answer_file))
                                                <div class="mt-2">
                                                    <a href="{{ Storage::url($ans->answer_file) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#123B60] hover:text-[#123B60] bg-[#E8F1F8] px-3 py-1.5 rounded-lg">
                                                        📎 Lihat Lampiran Jawaban Esai
                                                    </a>
                                                </div>
                                            @endif
                                        @else
                                            <div class="text-sm font-medium text-slate-700 bg-slate-50 p-3 rounded-lg border border-slate-200">
                                                {{ $q->question_type === 'pilihan_ganda' ? $displayAnswer : (is_array($ans->answer) ? implode(', ', $ans->answer) : $ans->answer) }}
                                            </div>
                                        @endif
                                    @endif
                                </div>

                            </div>
                        @empty
                            <p class="text-center text-sm text-slate-400 py-8">Ujian ini tidak memiliki soal.</p>
                        @endforelse
                    </div>
                    
                    <!-- Tombol Simpan Bawah (Hanya jika ada esai) -->
                    @if($essayCount > 0)
                        <div class="p-6 border-t border-slate-100 bg-slate-50 flex justify-end">
                            <button type="submit" class="text-sm font-bold text-white bg-[#123B60] hover:bg-[#0B2B48] px-6 py-2.5 rounded-xl shadow-sm transition-colors">
                                Simpan Nilai Esai
                            </button>
                        </div>
                    @endif
                </div>
            </form>

        </div>
    </div>
    
    <style>
        .prose img {
            max-width: 380px !important;
            height: auto !important;
            border-radius: 0.75rem;
            margin-top: 0.75rem;
            margin-bottom: 0.75rem;
        }
    </style>
</x-app-layout>