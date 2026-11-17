@extends('layouts.public')

@section('title', 'Cek Nilai Ujian | SMAS Methodist Banda Aceh')

@section('content')
    <div class="mx-auto w-full max-w-5xl px-4 py-12 sm:px-6 sm:py-16 lg:px-8">
        <div class="mx-auto max-w-2xl">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-navy transition hover:text-softblue">
                <span aria-hidden="true">&larr;</span> Kembali ke Beranda
            </a>

            <section class="mt-6 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl shadow-navy/5">
                <div class="bg-gradient-to-br from-[#0e172a] via-[#101C35] to-[#17365D] px-6 py-8 text-white sm:px-10">
                    <span class="inline-flex rounded-full border border-white/15 bg-white/10 px-3 py-1 text-[10px] font-bold uppercase tracking-[.18em] text-softblue">
                        Portal Orang Tua / Wali
                    </span>
                    <h1 class="mt-4 font-serif text-3xl font-bold sm:text-4xl">Cek Nilai Ujian</h1>
                    <p class="mt-3 max-w-xl text-sm leading-6 text-slate-300">
                        Masukkan NIS dan tanggal lahir siswa untuk melihat ujian yang telah diikuti, nilai objektif, koreksi esai, dan nilai akhir.
                    </p>
                </div>

                <form method="POST" action="{{ route('cek-nilai-ujian.show') }}" class="space-y-5 p-6 sm:p-10">
                    @csrf

                    @error('identity')
                        <div role="alert" class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">
                            {{ $message }}
                        </div>
                    @enderror

                    <div>
                        <label for="nis" class="mb-1.5 block text-sm font-semibold text-navy">
                            Nomor Induk Siswa (NIS)
                        </label>
                        <input
                            id="nis"
                            name="nis"
                            type="text"
                            inputmode="numeric"
                            pattern="[0-9]{1,20}"
                            maxlength="20"
                            autocomplete="off"
                            value="{{ old('nis') }}"
                            placeholder="Masukkan NIS siswa"
                            required
                            class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-softblue focus:ring-softblue"
                            aria-describedby="nis-help"
                        >
                        <p id="nis-help" class="mt-1.5 text-xs text-slate-500">Masukkan NIS yang terdaftar di sekolah.</p>
                        @error('nis')
                            <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="dob" class="mb-1.5 block text-sm font-semibold text-navy">
                            Tanggal Lahir Siswa
                        </label>
                        <input
                            id="dob"
                            name="dob"
                            type="date"
                            value="{{ old('dob') }}"
                            required
                            class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-softblue focus:ring-softblue"
                        >
                        @error('dob')
                            <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button
                        type="submit"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-navy px-5 py-3.5 text-sm font-bold text-white shadow-sm transition hover:bg-[#0B2B48] focus:outline-none focus:ring-2 focus:ring-softblue focus:ring-offset-2"
                    >
                        Tampilkan Hasil Ujian
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6"/>
                        </svg>
                    </button>

                    <p class="text-center text-xs leading-5 text-slate-500">
                        Demi menjaga privasi, hasil hanya ditampilkan setelah NIS dan tanggal lahir cocok.
                    </p>
                </form>
            </section>
        </div>
    </div>
@endsection
