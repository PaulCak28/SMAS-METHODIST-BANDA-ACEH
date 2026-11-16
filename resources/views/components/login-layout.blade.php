@php($schoolName = config('school.name', 'SMAS Methodist Banda Aceh'))
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk &middot; {{ $schoolName }}</title>

    {{-- Favicon / app icon: logo resmi sekolah --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=2">
    <link rel="icon" type="image/png" href="{{ asset('images/methodist-logo.png') }}?v=2">
    <link rel="apple-touch-icon" href="{{ asset('images/methodist-logo.png') }}?v=2">

    {{-- Fonts: Playfair Display (heading) + Plus Jakarta Sans (body).
         Fallback ke serif / system-font bila koneksi eksternal gagal. --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="school-login-page">
        <div class="sl-shell">

            {{-- ===================== KIRI: BRANDING ===================== --}}
            <aside class="sl-brand">
                {{-- Foto utama sekolah. Ganti file di public/images/school-hero.jpg kapan pun. --}}
                <img class="sl-brand-photo"
                     src="{{ asset('images/school-hero.jpg') }}"
                     alt="Suasana kegiatan siswa {{ $schoolName }}"
                     loading="eager" fetchpriority="high" decoding="async">
                <span class="sl-brand-scrim" aria-hidden="true"></span>
                <span class="sl-orb sl-orb--blue" aria-hidden="true"></span>
                <span class="sl-orb sl-orb--cream" aria-hidden="true"></span>
                <span class="sl-grid-lines" aria-hidden="true"></span>

                {{-- Header: logo + nama sekolah --}}
                <div class="sl-brand-head">
                    <span class="sl-logo" aria-hidden="true">
                        <x-school-emblem />
                    </span>
                    <div>
                        <p class="sl-brand-name">{{ $schoolName }}</p>
                        <p class="sl-brand-sub">Portal Pembelajaran Digital</p>
                    </div>
                </div>

                {{-- Tengah: badge + heading + accent + deskripsi --}}
                <div class="sl-brand-body">
                    <span class="sl-badge">School Learning Portal</span>

                    <h1 class="sl-headline">
                        Belajar, Berkembang,<br>dan <em>Berprestasi</em>
                    </h1>

                    <span class="sl-rule" aria-hidden="true"></span>

                    <p class="sl-desc">
                        Portal resmi pembelajaran {{ $schoolName }}. Satu akun
                        sekolah untuk kelas daring, ujian, materi pembelajaran,
                        dan informasi akademik.
                    </p>
                </div>

                {{-- Fitur portal --}}
                <ul class="sl-features">
                    <li class="sl-feature">
                        <span class="sl-feature-ic" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2.5" y="4" width="19" height="13" rx="2"/>
                                <path d="M8 21h8M12 17v4"/>
                                <path d="M10 8.5l4 2-4 2z" fill="currentColor" stroke="none"/>
                            </svg>
                        </span>
                        E-Class
                    </li>
                    <li class="sl-feature">
                        <span class="sl-feature-ic" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M8 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2h-2"/>
                                <rect x="8" y="2" width="8" height="4" rx="1"/>
                                <path d="M9 13l2 2 4-4"/>
                            </svg>
                        </span>
                        E-Exam
                    </li>
                    <li class="sl-feature">
                        <span class="sl-feature-ic" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H12v16H6.5A2.5 2.5 0 0 0 4 21.5z"/>
                                <path d="M20 5.5A2.5 2.5 0 0 0 17.5 3H12v16h5.5a2.5 2.5 0 0 1 2.5 2.5z"/>
                            </svg>
                        </span>
                        Materi Pembelajaran
                    </li>
                    <li class="sl-feature">
                        <span class="sl-feature-ic" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 11v2a1 1 0 0 0 1 1h3l6 4V6L7 10H4a1 1 0 0 0-1 1z"/>
                                <path d="M18 8a5 5 0 0 1 0 8"/>
                            </svg>
                        </span>
                        Informasi Akademik
                    </li>
                </ul>

                <p class="sl-brand-foot">&copy; {{ date('Y') }} {{ $schoolName }} &middot; Banda Aceh</p>
            </aside>

            {{-- ===================== KANAN: FORM ===================== --}}
            <main class="sl-panel">
                <div class="sl-panel-inner">
                    {{-- Brand ringkas untuk layar mobile (branding kiri disembunyikan) --}}
                    <div class="sl-brand-mobile">
                        <span class="sl-logo" aria-hidden="true">
                            <x-school-emblem />
                        </span>
                        <div>
                            <h2>{{ $schoolName }}</h2>
                            <p>School Learning Portal</p>
                        </div>
                    </div>

                    {{ $slot }}
                </div>
            </main>

        </div>
    </div>
</body>
</html>