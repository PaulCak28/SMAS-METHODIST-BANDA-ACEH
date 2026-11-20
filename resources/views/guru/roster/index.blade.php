<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[.2em] text-[#267BB5]">
                    Methodist Learning Space
                </p>
                <h2 class="text-xl font-bold text-slate-800">
                    Roster Pelajaran &amp; Jadwal Mengajar
                </h2>
            </div>

            <div class="mt-2 flex flex-wrap items-center gap-2 sm:mt-0">
                <span class="inline-flex items-center gap-2 rounded-full border border-blue-100 bg-blue-50 px-3.5 py-1.5 text-xs font-bold text-[#123B60]">
                    <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Tahun Pelajaran {{ $metadata['academic_year'] }}
                </span>

                <button
                    type="button"
                    onclick="window.print()"
                    class="inline-flex items-center gap-2 rounded-full bg-[#123B60] px-4 py-1.5 text-xs font-bold text-white shadow-sm transition hover:bg-[#267BB5] focus:outline-none"
                >
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4H7v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    <span>Cetak Roster Resmi</span>
                </button>
            </div>
        </div>
    </x-slot>

    <div
        x-data="{
            viewMode: 'matrix', // 'matrix' atau 'my_schedule'
            activeDay: 'all',
            activeClass: 'all',
            selectedTeacher: '{{ !empty($currentTeacherCodes) ? $currentTeacherCodes[0] : '' }}',
            myTeacherCodes: {{ json_encode($currentTeacherCodes) }},
            showLegend: false,
            highlightTeacher(code) {
                if (!this.selectedTeacher) return false;
                return this.selectedTeacher === code;
            },
            isMySlot(code) {
                return this.myTeacherCodes.includes(code);
            }
        }"
        class="space-y-6 py-4"
    >
        {{-- ========================================================
             BANNER DOKUMEN RESMI (KOP ROSTER)
             ======================================================== --}}
        <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-gradient-to-r from-[#101C35] via-[#123B60] to-[#1B4D7E] p-6 text-white shadow-md print:border-none print:bg-white print:p-0 print:text-slate-900 print:shadow-none">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white p-2 shadow-inner print:h-12 print:w-12">
                        <img src="{{ asset('images/methodist-logo.png') }}" alt="Logo Methodist" class="h-full w-full object-contain">
                    </div>
                    <div>
                        <div class="inline-flex items-center gap-2 rounded-md bg-white/10 px-2.5 py-0.5 text-[10px] font-bold tracking-widest uppercase text-[#8eadd0] print:hidden">
                            Dokumen Resmi Kurikulum
                        </div>
                        <h1 class="text-xl sm:text-2xl font-black tracking-tight print:text-xl print:text-[#101C35]">
                            ROSTER PELAJARAN TAHUN {{ $metadata['academic_year'] }}
                        </h1>
                        <p class="text-xs text-slate-200 sm:text-sm print:text-xs print:text-slate-600">
                            {{ $metadata['school_name'] }} &bull; Berlaku Efektif Semester Ganjil &amp; Genap
                        </p>
                    </div>
                </div>

                {{-- STATISTIK KILAT GURU --}}
                <div class="flex flex-wrap items-center gap-2 border-t border-white/10 pt-3 md:border-t-0 md:pt-0 print:hidden">
                    <button
                        type="button"
                        @click="viewMode = 'matrix'"
                        :class="viewMode === 'matrix' ? 'bg-white text-[#123B60] shadow-sm' : 'bg-white/10 text-white hover:bg-white/20'"
                        class="rounded-xl px-3.5 py-2 text-xs font-bold transition flex items-center gap-1.5"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                            <path d="M3 9h18M9 21V9"/>
                        </svg>
                        <span>Matrix Roster</span>
                    </button>

                    <button
                        type="button"
                        @click="viewMode = 'duties'"
                        :class="viewMode === 'duties' ? 'bg-white text-[#123B60] shadow-sm' : 'bg-white/10 text-white hover:bg-white/20'"
                        class="rounded-xl px-3.5 py-2 text-xs font-bold transition flex items-center gap-1.5"
                    >
                        <span>👥</span>
                        <span>Pembagian Tugas Guru</span>
                    </button>

                    <button
                        type="button"
                        @click="viewMode = 'my_schedule'"
                        :class="viewMode === 'my_schedule' ? 'bg-[#8eadd0] text-[#101C35] font-extrabold shadow-sm' : 'bg-white/10 text-white hover:bg-white/20'"
                        class="rounded-xl px-3.5 py-2 text-xs font-bold transition flex items-center gap-1.5"
                    >
                        <span class="flex h-2 w-2 rounded-full bg-emerald-400"></span>
                        <span>Jadwal Saya</span>
                    </button>
                </div>
            </div>

            {{-- Watermark background print/display --}}
            <div class="absolute -right-6 -bottom-10 pointer-events-none opacity-5 text-9xl font-black print:hidden">
                METHODIST
            </div>
        </div>

        {{-- ========================================================
             TOOLBAR FILTER & KONTROL INTERAKTIF (TIDAK TERCETAK DI PRINT)
             ======================================================== --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm print:hidden">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                {{-- TAB FILTER HARI --}}
                <div class="flex flex-wrap items-center gap-1.5">
                    <span class="mr-2 text-xs font-bold uppercase tracking-wider text-slate-400">Hari:</span>
                    <button
                        type="button"
                        @click="activeDay = 'all'"
                        :class="activeDay === 'all' ? 'bg-[#123B60] text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                        class="rounded-lg px-3 py-1.5 text-xs font-bold transition"
                    >
                        Semua Hari
                    </button>
                    @foreach (['senin' => 'Senin', 'selasa' => 'Selasa', 'rabu' => 'Rabu', 'kamis' => 'Kamis', 'jumat' => 'Jumat', 'sabtu' => 'Sabtu'] as $dayKey => $dayLabel)
                        <button
                            type="button"
                            @click="activeDay = '{{ $dayKey }}'"
                            :class="activeDay === '{{ $dayKey }}' ? 'bg-[#123B60] text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                            class="rounded-lg px-3 py-1.5 text-xs font-bold transition"
                        >
                            {{ $dayLabel }}
                        </button>
                    @endforeach
                </div>

                {{-- FILTER KELAS & HIGHLIGHT GURU --}}
                <div class="flex flex-wrap items-center gap-3">
                    {{-- Filter Kelas --}}
                    <div class="flex items-center gap-2">
                        <label for="filter-class" class="text-xs font-bold text-slate-500 whitespace-nowrap">Filter Kelas:</label>
                        <select
                            id="filter-class"
                            x-model="activeClass"
                            class="rounded-xl border-slate-200 bg-slate-50 py-1.5 pl-3 pr-8 text-xs font-semibold text-slate-700 focus:border-[#267BB5] focus:ring focus:ring-blue-100"
                        >
                            <option value="all">Semua Kelas (6 Kelas)</option>
                            @foreach ($classes as $classKey => $classInfo)
                                <option value="{{ $classKey }}">{{ $classKey }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Highlight Guru --}}
                    <div class="flex items-center gap-2">
                        <label for="highlight-teacher" class="text-xs font-bold text-slate-500 whitespace-nowrap">Sorot Guru:</label>
                        <select
                            id="highlight-teacher"
                            x-model="selectedTeacher"
                            class="rounded-xl border-slate-200 bg-slate-50 py-1.5 pl-3 pr-8 text-xs font-semibold text-slate-700 focus:border-[#267BB5] focus:ring focus:ring-blue-100"
                        >
                            <option value="">-- Tanpa Sorotan --</option>
                            @if (!empty($currentTeacherCodes))
                                <optgroup label="Akun Anda ({{ auth()->user()->name }})">
                                    @foreach ($currentTeacherCodes as $myCode)
                                        <option value="{{ $myCode }}">★ {{ $myCode }} - {{ $teachers[$myCode]['desc'] ?? $teachers[$myCode]['name'] }}</option>
                                    @endforeach
                                </optgroup>
                            @endif
                            <optgroup label="Semua Kode Guru Resmi">
                                @foreach ($teachers as $tCode => $tData)
                                    <option value="{{ $tCode }}">{{ $tCode }} - {{ $tData['name'] }} ({{ $tData['subject'] }})</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>

                    {{-- Tombol Bantuan Legenda --}}
                    <button
                        type="button"
                        @click="showLegend = !showLegend"
                        class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-bold text-slate-700 transition hover:bg-slate-100"
                    >
                        <span x-text="showLegend ? 'Tutup Legenda' : 'Lihat Kode Guru & Mapel ℹ'"></span>
                    </button>
                </div>
            </div>

            {{-- KOTAK KETERANGAN GURU YANG DISOROT --}}
            <template x-if="selectedTeacher">
                <div class="mt-3 flex items-center justify-between rounded-xl bg-blue-50/80 px-4 py-2.5 text-xs border border-blue-100">
                    <div class="flex items-center gap-2">
                        <span class="flex h-5 w-5 items-center justify-center rounded-full bg-[#123B60] text-[10px] font-black text-white">
                            ★
                        </span>
                        <span class="text-slate-600">
                            Menyorot jadwal untuk kode guru:
                            <strong class="font-bold text-[#123B60]" x-text="selectedTeacher"></strong>
                            <template x-if="isMySlot(selectedTeacher)">
                                <span class="ml-1 rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-bold text-emerald-800">
                                    Jadwal Mengajar Anda
                                </span>
                            </template>
                        </span>
                    </div>

                    <button
                        type="button"
                        @click="selectedTeacher = ''"
                        class="text-[11px] font-bold text-slate-400 hover:text-slate-700 underline"
                    >
                        Hapus Sorotan
                    </button>
                </div>
            </template>
        </div>

        {{-- ========================================================
             MODAL / COLLAPSIBLE LEGENDA KODE GURU & MATA PELAJARAN
             ======================================================== --}}
        <div
            x-show="showLegend"
            x-collapse
            class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm print:hidden"
        >
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <span>📋</span>
                    <span>Daftar Kode Guru &amp; Mata Pelajaran Resmi SMAS Methodist</span>
                </h3>
                <button type="button" @click="showLegend = false" class="text-xs font-bold text-slate-400 hover:text-slate-600">
                    Tutup ✕
                </button>
            </div>

            <div class="mt-4 grid gap-6 md:grid-cols-2">
                {{-- Daftar Kode Guru --}}
                <div>
                    <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-400 mb-2">Kode Singkatan Guru</h4>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        @foreach ($teachers as $code => $info)
                            <div
                                @click="selectedTeacher = '{{ $code }}'; showLegend = false;"
                                class="flex cursor-pointer items-center justify-between rounded-lg border border-slate-100 bg-slate-50/60 p-2 transition hover:border-[#267BB5] hover:bg-blue-50/50"
                            >
                                <span class="font-black text-[#123B60]">{{ $code }}</span>
                                <span class="text-slate-600 truncate ml-2 text-[11px]">{{ $info['name'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Daftar Mata Pelajaran & Palet Warna --}}
                <div>
                    <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-400 mb-2">Kode &amp; Warna Mata Pelajaran</h4>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        @foreach ($subjectStyles as $subCode => $subStyle)
                            <div class="flex items-center gap-2 rounded-lg border border-slate-100 bg-slate-50 p-2">
                                <span class="inline-block rounded px-2 py-0.5 text-[10px] font-black uppercase text-white shadow-xs" style="background-color: {{ $subStyle['bg'] }}; color: {{ $subStyle['text'] }};">
                                    {{ $subCode }}
                                </span>
                                <span class="text-slate-600 truncate text-[11px]">{{ $subStyle['name'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================================
             TAMPILAN 1: MATRIX ROSTER SEKOLAH (SESUAI DOKUMEN RESMI)
             ======================================================== --}}
        <div x-show="viewMode === 'matrix'" class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm print:rounded-none print:border-black print:shadow-none">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[1200px] table-fixed border-collapse text-center text-xs print:text-[9px]">
                    <colgroup>
                        <col style="width: 80px;">  {{-- Hari --}}
                        <col style="width: 45px;">  {{-- Les --}}
                        <col style="width: 105px;"> {{-- Waktu --}}
                        {{-- 6 Kelas x 2 kolom = 12 kolom --}}
                        <col style="width: 80px;">  {{-- X IPAS A Mapel --}}
                        <col style="width: 65px;">  {{-- X IPAS A Guru --}}
                        <col style="width: 80px;">  {{-- X IPAS B Mapel --}}
                        <col style="width: 65px;">  {{-- X IPAS B Guru --}}
                        <col style="width: 80px;">  {{-- XI A Mapel --}}
                        <col style="width: 65px;">  {{-- XI A Guru --}}
                        <col style="width: 80px;">  {{-- XI B Mapel --}}
                        <col style="width: 65px;">  {{-- XI B Guru --}}
                        <col style="width: 80px;">  {{-- XII A Mapel --}}
                        <col style="width: 65px;">  {{-- XII A Guru --}}
                        <col style="width: 80px;">  {{-- XII B Mapel --}}
                        <col style="width: 65px;">  {{-- XII B Guru --}}
                    </colgroup>
                    <thead class="sticky top-0 z-20">
                        <tr class="border-b border-white/20 bg-[#123B60] text-white print:bg-slate-200 print:text-black">
                            <th rowspan="2" class="border-r border-white/20 px-2 py-3 font-bold uppercase tracking-wider print:border-black">
                                Hari
                            </th>
                            <th rowspan="2" class="border-r border-white/20 px-2 py-3 font-bold uppercase tracking-wider print:border-black">
                                Les
                            </th>
                            <th rowspan="2" class="border-r border-white/20 px-2 py-3 font-bold uppercase tracking-wider print:border-black">
                                Waktu
                            </th>
                            @foreach ($classes as $classKey => $classMeta)
                                <th
                                    colspan="2"
                                    class="border-r border-white/20 px-2 py-2 font-black uppercase tracking-wider last:border-r-0 print:border-black"
                                >
                                    {{ $classKey }}
                                </th>
                            @endforeach
                        </tr>
                        <tr class="border-b border-slate-200 bg-[#1B4D7E] text-[10px] font-bold text-slate-100 print:bg-slate-100 print:text-black">
                            @foreach ($classes as $classKey => $classMeta)
                                <th class="border-r border-white/20 px-1 py-1.5 print:border-black">
                                    Bid Studi
                                </th>
                                <th class="border-r border-white/20 px-1 py-1.5 last:border-r-0 print:border-black">
                                    Kode Guru
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700 print:divide-black">
                        @foreach ($rosterData as $dayKey => $day)
                            {{-- Baris Pemisah Hari --}}
                            <tr
                                x-show="activeDay === 'all' || activeDay === '{{ $dayKey }}'"
                                class="bg-[#123B60] text-white print:bg-slate-200 print:text-black border-y-2 border-[#123B60]"
                            >
                                <td colspan="15" class="py-2.5 px-4 text-left font-black tracking-wider uppercase text-xs">
                                    <span class="inline-flex items-center gap-2">
                                        <span>📅</span>
                                        <span>HARI {{ $day['day_name'] }}</span>
                                        <span class="text-[10px] font-normal text-slate-300 ml-2 print:text-slate-700">SMAS Methodist Banda Aceh</span>
                                    </span>
                                </td>
                            </tr>

                            @foreach ($day['rows'] as $row)
                                @if (!empty($row['is_special']))
                                    {{-- Baris Khusus: Upacara / Kebaktian --}}
                                    <tr
                                        x-show="activeDay === 'all' || activeDay === '{{ $dayKey }}'"
                                        class="bg-amber-50/60 print:bg-slate-100"
                                    >
                                        <td class="border-r border-slate-200 px-2 py-2.5 font-bold uppercase text-[#123B60] print:border-black">
                                            {{ $day['day_name'] }}
                                        </td>
                                        <td class="border-r border-slate-200 px-2 py-2.5 font-black text-slate-800 print:border-black">
                                            {{ $row['les'] }}
                                        </td>
                                        <td class="border-r border-slate-200 px-2 py-2.5 font-bold whitespace-nowrap text-slate-600 print:border-black">
                                            {{ $row['time'] }}
                                        </td>
                                        <td
                                            colspan="12"
                                            class="py-2.5 text-center text-xs font-black uppercase tracking-[.3em] text-[#123B60] bg-gradient-to-r from-blue-50/60 via-indigo-50/70 to-blue-50/60 border-y border-blue-200/50 print:border-black"
                                        >
                                            {{ $row['special_title'] }}
                                        </td>
                                    </tr>
                                @elseif (!empty($row['is_break']))
                                    {{-- Baris Istirahat --}}
                                    <tr
                                        x-show="activeDay === 'all' || activeDay === '{{ $dayKey }}'"
                                        class="bg-slate-100/80 print:bg-slate-200"
                                    >
                                        <td class="border-r border-slate-200 px-2 py-1.5 font-bold text-slate-400 print:border-black">
                                            —
                                        </td>
                                        <td class="border-r border-slate-200 px-2 py-1.5 font-bold text-slate-400 print:border-black">
                                            —
                                        </td>
                                        <td class="border-r border-slate-200 px-2 py-1.5 font-bold whitespace-nowrap text-slate-500 print:border-black">
                                            {{ $row['time'] }}
                                        </td>
                                        <td
                                            colspan="12"
                                            class="py-1.5 text-center text-[11px] font-black uppercase tracking-[.3em] text-slate-500 print:border-black"
                                        >
                                            {{ $row['break_title'] }}
                                        </td>
                                    </tr>
                                @else
                                    {{-- Baris Pelajaran Reguler --}}
                                    <tr
                                        x-show="activeDay === 'all' || activeDay === '{{ $dayKey }}'"
                                        class="hover:bg-blue-50/30 transition print:hover:bg-transparent"
                                    >
                                        <td class="border-r border-slate-200 px-2 py-2 font-bold uppercase text-slate-500 print:border-black align-middle">
                                            {{ $day['day_name'] }}
                                        </td>
                                        <td class="border-r border-slate-200 px-2 py-2 font-black text-slate-800 print:border-black align-middle">
                                            {{ $row['les'] }}
                                        </td>
                                        <td class="border-r border-slate-200 px-2 py-2 font-semibold whitespace-nowrap text-slate-600 print:border-black align-middle text-[11px]">
                                            {{ $row['time'] }}
                                        </td>

                                        @foreach ($classes as $classKey => $classMeta)
                                            @php
                                                $slot = $row['slots'][$classKey] ?? null;
                                                $subjectCode = $slot['subject'] ?? '';
                                                $teacherCode = $slot['teacher'] ?? '';
                                                $style = $subjectStyles[$subjectCode] ?? [
                                                    'bg' => '#64748B',
                                                    'text' => '#FFFFFF',
                                                ];
                                            @endphp

                                            {{-- Kolom Bid Studi --}}
                                            <td
                                                class="border-r border-slate-200 px-1.5 py-2 font-bold transition print:border-black align-middle"
                                                :class="{
                                                    'ring-2 ring-blue-500 ring-inset bg-blue-100 font-black': highlightTeacher('{{ $teacherCode }}'),
                                                    'opacity-30': selectedTeacher && !highlightTeacher('{{ $teacherCode }}')
                                                }"
                                            >
                                                @if ($slot)
                                                    <span
                                                        class="inline-block w-full max-w-[70px] truncate rounded px-1.5 py-0.5 text-[10px] font-black uppercase tracking-wider shadow-xs print:border print:border-black"
                                                        style="background-color: {{ $style['bg'] }}; color: {{ $style['text'] }};"
                                                        title="{{ $subjectStyles[$subjectCode]['name'] ?? $subjectCode }}"
                                                    >
                                                        {{ $subjectCode }}
                                                    </span>
                                                @else
                                                    <span class="text-slate-300 font-normal">—</span>
                                                @endif
                                            </td>

                                            {{-- Kolom Kode Guru --}}
                                            <td
                                                class="border-r border-slate-200 px-1 py-2 font-black text-slate-800 last:border-r-0 transition print:border-black align-middle"
                                                :class="{
                                                    'ring-2 ring-blue-500 ring-inset bg-blue-100 text-blue-900 scale-105 font-black': highlightTeacher('{{ $teacherCode }}'),
                                                    'opacity-30': selectedTeacher && !highlightTeacher('{{ $teacherCode }}')
                                                }"
                                            >
                                                @if ($slot)
                                                    <button
                                                        type="button"
                                                        @click="selectedTeacher = (selectedTeacher === '{{ $teacherCode }}' ? '' : '{{ $teacherCode }}')"
                                                        title="{{ $teachers[$teacherCode]['name'] ?? $teacherCode }} (Klik untuk sorot)"
                                                        class="rounded px-1.5 py-0.5 text-[11px] font-black text-slate-800 hover:bg-slate-200 transition focus:outline-none"
                                                    >
                                                        {{ $teacherCode }}
                                                    </button>
                                                @else
                                                    <span class="text-slate-300 font-normal">—</span>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endif
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ========================================================
             TAMPILAN 2: JADWAL SAYA (KARTU MENGAJAR BUDI SANTOSO / USER)
             ======================================================== --}}
        <div x-show="viewMode === 'my_schedule'" class="space-y-6">
            <div class="rounded-2xl border border-blue-200 bg-blue-50/60 p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <div class="inline-flex items-center gap-2 rounded-full bg-blue-100 px-3 py-1 text-xs font-bold text-blue-800">
                            <span>👤</span>
                            <span>Jadwal Khusus Guru</span>
                        </div>
                        <h3 class="text-xl font-black text-[#101C35] mt-2">
                            Jadwal Mengajar Anda: {{ auth()->user()->name }}
                        </h3>
                        <p class="text-xs text-slate-600 mt-1">
                            Kode Guru Terdaftar:
                            @foreach ($currentTeacherCodes as $c)
                                <span class="font-extrabold text-[#123B60] underline">{{ $c }} ({{ $teachers[$c]['desc'] ?? $teachers[$c]['subject'] }})</span>@if(!$loop->last), @endif
                            @endforeach
                        </p>
                    </div>

                    <a
                        href="{{ route('guru.classes.index') }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-[#123B60] px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-[#267BB5]"
                    >
                        <span>Buka E-Class (Ruang Belajar)</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>

            @if (empty($mySchedule))
                <div class="rounded-2xl border border-slate-200 bg-white p-12 text-center text-slate-400">
                    <p class="text-base font-bold text-slate-600">Tidak ada jadwal mengajar yang terdaftar untuk akun ini.</p>
                    <p class="text-xs text-slate-400 mt-1">Silakan gunakan tab "Matrix Roster Sekolah" untuk melihat jadwal seluruh kelas.</p>
                </div>
            @else
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($mySchedule as $dayKey => $group)
                        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                            <div class="border-b border-slate-100 bg-gradient-to-r from-blue-50/80 to-white px-5 py-3.5 flex items-center justify-between">
                                <h4 class="font-black text-sm uppercase text-[#123B60] flex items-center gap-2">
                                    <span>📅</span>
                                    <span>Hari {{ $group['day_name'] }}</span>
                                </h4>
                                <span class="rounded-full bg-blue-100 px-2.5 py-0.5 text-[10px] font-bold text-blue-800">
                                    {{ count($group['items']) }} Jam / Sesi
                                </span>
                            </div>

                            <div class="divide-y divide-slate-100 p-2">
                                @foreach ($group['items'] as $item)
                                    @php
                                        $subStyle = $subjectStyles[$item['subject']] ?? ['bg' => '#123B60', 'text' => '#fff'];
                                    @endphp
                                    <div class="flex items-center justify-between rounded-xl p-3 hover:bg-slate-50 transition">
                                        <div class="flex items-center gap-3">
                                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-100 font-extrabold text-xs text-[#123B60]">
                                                Les {{ $item['les'] }}
                                            </span>
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <span class="font-extrabold text-xs text-slate-800">{{ $item['class'] }}</span>
                                                    <span class="rounded px-1.5 py-0.5 text-[9px] font-black uppercase text-white" style="background-color: {{ $subStyle['bg'] }}; color: {{ $subStyle['text'] }};">
                                                        {{ $item['subject'] }}
                                                    </span>
                                                </div>
                                                <p class="text-[11px] font-semibold text-slate-400 mt-0.5">
                                                    ⏰ {{ $item['time'] }} WIB
                                                </p>
                                            </div>
                                        </div>

                                        <span class="rounded-md border border-slate-200 bg-slate-50 px-2 py-1 text-[10px] font-bold text-slate-600">
                                            Kode: {{ $item['teacher'] }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ========================================================
             TAMPILAN 3: PEMBAGIAN TUGAS GURU (SK BEBAN MENGAJAR RESMI)
             ======================================================== --}}
        <div x-show="viewMode === 'duties'" class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-100 pb-4">
                    <div>
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-[#123B60]">
                            <span>📑</span>
                            <span>SK Pembagian Tugas Guru TP {{ $metadata['academic_year'] }}</span>
                        </span>
                        <h3 class="text-xl font-black text-[#101C35] mt-2">
                            Pembagian Tugas Guru SMA Methodist Banda Aceh
                        </h3>
                        <p class="text-xs text-slate-500 mt-1">
                            Alokasi jam mengajar resmi per kelas (X IPAS A s/d XII B) dan tugas tambahan guru.
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="rounded-xl bg-slate-50 px-4 py-2 border border-slate-200 text-right">
                            <span class="block text-[10px] font-bold uppercase text-slate-400">Total Jam Mengajar</span>
                            <span class="text-lg font-black text-[#123B60]">296 Jam / Minggu</span>
                        </div>
                    </div>
                </div>

                {{-- TABEL PEMBAGIAN TUGAS GURU --}}
                <div class="overflow-x-auto mt-4">
                    <table class="w-full min-w-[1050px] table-fixed border-collapse text-xs print:text-[10px]">
                        <colgroup>
                            <col style="width: 45px;">   {{-- No --}}
                            <col style="width: 220px;">  {{-- Nama Guru --}}
                            <col style="width: 190px;">  {{-- Jabatan --}}
                            <col style="width: 150px;">  {{-- Mapel --}}
                            <col style="width: 65px;">   {{-- X IPAS A --}}
                            <col style="width: 65px;">   {{-- X IPAS B --}}
                            <col style="width: 65px;">   {{-- XI A --}}
                            <col style="width: 65px;">   {{-- XI B --}}
                            <col style="width: 65px;">   {{-- XII A --}}
                            <col style="width: 65px;">   {{-- XII B --}}
                            <col style="width: 70px;">   {{-- Jumlah --}}
                        </colgroup>
                        <thead>
                            <tr class="bg-[#123B60] text-white print:bg-slate-200 print:text-black">
                                <th rowspan="2" class="border border-white/20 px-2 py-3 font-bold text-center">No</th>
                                <th rowspan="2" class="border border-white/20 px-3 py-3 font-bold text-left">Nama Guru &amp; Tenaga Pendidik</th>
                                <th rowspan="2" class="border border-white/20 px-3 py-3 font-bold text-left">Jabatan / Tugas Tambahan</th>
                                <th rowspan="2" class="border border-white/20 px-3 py-3 font-bold text-left">Mata Pelajaran</th>
                                <th colspan="6" class="border border-white/20 px-2 py-2 font-bold text-center">Jam Mengajar / Kelas</th>
                                <th rowspan="2" class="border border-white/20 px-2 py-3 font-bold text-center bg-[#0B2B48]">Jumlah</th>
                            </tr>
                            <tr class="bg-[#1B4D7E] text-[10px] font-bold text-slate-100 print:bg-slate-100 print:text-black text-center">
                                <th class="border border-white/20 px-1 py-1.5">X IPAS A</th>
                                <th class="border border-white/20 px-1 py-1.5">X IPAS B</th>
                                <th class="border border-white/20 px-1 py-1.5">XI A</th>
                                <th class="border border-white/20 px-1 py-1.5">XI B</th>
                                <th class="border border-white/20 px-1 py-1.5">XII A</th>
                                <th class="border border-white/20 px-1 py-1.5">XII B</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach ($teacherDuties as $duty)
                                <tr class="hover:bg-blue-50/40 transition {{ $duty['no'] % 2 === 0 ? 'bg-slate-50/50' : 'bg-white' }}">
                                    <td class="border border-slate-200 px-2 py-2.5 text-center font-bold text-slate-500">
                                        {{ $duty['no'] }}
                                    </td>
                                    <td class="border border-slate-200 px-3 py-2.5 font-bold text-[#101C35]">
                                        <div class="flex items-center justify-between gap-2">
                                            <span>{{ $duty['name'] }}</span>
                                            @if ($duty['code'])
                                                <span class="rounded bg-slate-100 px-1.5 py-0.5 text-[10px] font-black text-[#123B60] border border-slate-200">
                                                    {{ $duty['code'] }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="border border-slate-200 px-3 py-2.5 text-slate-600 font-medium">
                                        {{ $duty['role'] }}
                                    </td>
                                    <td class="border border-slate-200 px-3 py-2.5 font-semibold text-[#123B60]">
                                        {{ $duty['subject'] }}
                                    </td>
                                    @foreach (['X IPAS A', 'X IPAS B', 'XI A', 'XI B', 'XII A', 'XII B'] as $cName)
                                        <td class="border border-slate-200 px-2 py-2.5 text-center font-bold {{ $duty['hours'][$cName] ? 'text-slate-900 bg-blue-50/20' : 'text-slate-300' }}">
                                            {{ $duty['hours'][$cName] ?? '—' }}
                                        </td>
                                    @endforeach
                                    <td class="border border-slate-200 px-2 py-2.5 text-center font-black text-[#123B60] bg-slate-50">
                                        {{ $duty['total'] ?? '—' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-[#123B60] text-white font-black text-xs print:bg-slate-300 print:text-black text-center">
                                <td colspan="4" class="border border-white/20 px-4 py-3 text-right uppercase tracking-wider">
                                    Total Jam Pelajaran / Minggu:
                                </td>
                                <td class="border border-white/20 px-2 py-3">50</td>
                                <td class="border border-white/20 px-2 py-3">50</td>
                                <td class="border border-white/20 px-2 py-3">50</td>
                                <td class="border border-white/20 px-2 py-3">50</td>
                                <td class="border border-white/20 px-2 py-3">48</td>
                                <td class="border border-white/20 px-2 py-3">48</td>
                                <td class="border border-white/20 px-2 py-3 bg-[#0B2B48] text-sm text-yellow-300">296</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
