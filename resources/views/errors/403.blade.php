@extends('layouts.public')

@section('title', '403 - Akses Ditolak | SMAS Methodist Banda Aceh')

@section('content')
    @php
        $dashboardRoute = match (auth()->user()?->role) {
            'admin' => 'admin.dashboard',
            'guru' => 'guru.dashboard',
            'siswa' => 'siswa.dashboard',
            default => null,
        };
        $errorMessage = $exception->getMessage();
    @endphp

    <div class="mx-auto flex min-h-[65vh] w-full max-w-6xl items-center px-4 py-12 sm:px-6 lg:px-8">
        <section class="grid w-full overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl shadow-navy/5 md:grid-cols-[0.85fr_1.15fr]">
            <div class="relative flex min-h-64 flex-col items-center justify-center overflow-hidden bg-gradient-to-br from-[#0e172a] via-[#101C35] to-[#17365D] p-8 text-white sm:min-h-80">
                <div class="absolute -right-16 -top-20 h-56 w-56 rounded-full bg-softblue/15 blur-3xl"></div>
                <div class="absolute -bottom-24 -left-12 h-56 w-56 rounded-full bg-sky-400/10 blur-3xl"></div>
                <div class="relative flex h-20 w-20 items-center justify-center rounded-2xl border border-white/15 bg-white/10 text-softblue shadow-lg backdrop-blur">
                    <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 10V7a4.5 4.5 0 019 0v3m-10 0h11a2 2 0 012 2v7a2 2 0 01-2 2h-11a2 2 0 01-2-2v-7a2 2 0 012-2zm5.5 4v3"/>
                    </svg>
                </div>
                <p class="relative mt-5 font-serif text-6xl font-bold tracking-tight">403</p>
                <p class="relative mt-1 text-xs font-bold uppercase tracking-[.2em] text-slate-300">Akses Dibatasi</p>
            </div>

            <div class="flex flex-col justify-center p-7 sm:p-10 lg:p-12">
                <span class="inline-flex w-fit rounded-full border border-blue-100 bg-blue-50 px-3 py-1 text-[10px] font-bold uppercase tracking-[.18em] text-navy">
                    Portal Akademik SMAS Methodist
                </span>
                <h1 class="mt-5 font-serif text-3xl font-bold text-navy sm:text-4xl">Halaman ini tidak bisa dibuka</h1>
                <p class="mt-3 text-sm leading-6 text-slate-600">
                    Akun Anda tidak memiliki izin untuk mengakses halaman tersebut. Pastikan Anda masuk dengan akun yang sesuai.
                </p>

                @if ($errorMessage && $errorMessage !== 'Forbidden')
                    <p class="mt-4 rounded-xl border border-amber-100 bg-amber-50 px-4 py-3 text-sm leading-6 text-amber-900">
                        {{ $errorMessage }}
                    </p>
                @endif

                <div class="mt-7 flex flex-wrap gap-3">
                    @if ($dashboardRoute)
                        <a href="{{ route($dashboardRoute) }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-navy px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-[#0B2B48] focus:outline-none focus:ring-2 focus:ring-softblue focus:ring-offset-2">
                            <span aria-hidden="true">&larr;</span>
                            Kembali ke Dashboard
                        </a>
                    @else
                        <a href="{{ url('/') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-navy px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-[#0B2B48] focus:outline-none focus:ring-2 focus:ring-softblue focus:ring-offset-2">
                            <span aria-hidden="true">&larr;</span>
                            Kembali ke Beranda
                        </a>
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-navy transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-softblue focus:ring-offset-2">
                            Masuk dengan Akun Lain
                        </a>
                    @endif
                </div>
            </div>
        </section>
    </div>
@endsection
