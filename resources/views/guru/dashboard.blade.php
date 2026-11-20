<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[.2em] text-[#267BB5]">
                    Methodist Learning Space
                </p>
                <h2 class="text-xl font-bold text-slate-800">
                    Dashboard Guru
                </h2>
            </div>

            <div class="mt-2 inline-flex w-fit items-center gap-2 rounded-full border border-blue-100 bg-blue-50 px-4 py-2 text-xs font-semibold text-blue-800 sm:mt-0">
                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                Ruang Kerja Guru
            </div>
        </div>
    </x-slot>

    @php
        /*
        |--------------------------------------------------------------------------
        | STATISTIK DASHBOARD GURU
        |--------------------------------------------------------------------------
        */
        $totalClasses = $overview->count();
        $totalStudents = $overview->sum('student_count');
        $totalAssignments = $overview->sum('assignment_count');
        $totalExams = $overview->sum('exam_count');
        $totalUngraded = $overview->sum('ungraded_essays');

        /*
        |--------------------------------------------------------------------------
        | TEMA VISUAL MATA PELAJARAN
        |--------------------------------------------------------------------------
        */
        $subjectThemes = [
            'matematika' => [
                'icon' => '∑',
                'label' => 'MATEMATIKA',
                'description' => 'Eksplorasi angka, pola, dan logika berpikir',
                'background' => 'linear-gradient(135deg,#123B60,#267BB5)',
            ],
            'bahasa indonesia' => [
                'icon' => 'Aa',
                'label' => 'BAHASA & SASTRA INDONESIA',
                'description' => 'Mengembangkan literasi dan kekuatan berbahasa',
                'background' => 'linear-gradient(135deg,#123B60,#267BB5)',
            ],
            'bahasa inggris' => [
                'icon' => 'Ab',
                'label' => 'BAHASA INGGRIS',
                'description' => 'Berkomunikasi dan terhubung secara global',
                'background' => 'linear-gradient(135deg,#123B60,#267BB5)',
            ],
            'biologi' => [
                'icon' => '✿',
                'label' => 'BIOLOGI',
                'description' => 'Menjelajahi dunia kehidupan dan ekosistem',
                'background' => 'linear-gradient(135deg,#123B60,#267BB5)',
            ],
            'fisika' => [
                'icon' => '⚛',
                'label' => 'FISIKA',
                'description' => 'Memahami hukum alam dan prinsip semesta',
                'background' => 'linear-gradient(135deg,#123B60,#267BB5)',
            ],
            'kimia' => [
                'icon' => '⚗',
                'label' => 'KIMIA',
                'description' => 'Mempelajari materi, zat, dan reaksi kimia',
                'background' => 'linear-gradient(135deg,#123B60,#267BB5)',
                'image' => asset('images/subjects/kimia.jpg'),
            ],
            'sejarah' => [
                'icon' => '◷',
                'label' => 'SEJARAH',
                'description' => 'Mengambil hikmah dan perjalanan masa lalu',
                'background' => 'linear-gradient(135deg,#123B60,#267BB5)',
            ],
            'informatika' => [
                'icon' => '</>',
                'label' => 'INFORMATIKA',
                'description' => 'Membangun logika digital dan komputasi',
                'background' => 'linear-gradient(135deg,#123B60,#267BB5)',
            ],
            'agama' => [
                'icon' => '✧',
                'label' => 'PENDIDIKAN AGAMA',
                'description' => 'Membina karakter, iman, dan pekerti mulia',
                'background' => 'linear-gradient(135deg,#123B60,#267BB5)',
            ],
            'ekonomi' => [
                'icon' => '↗',
                'label' => 'EKONOMI',
                'description' => 'Memahami pasar, bisnis, dan keuangan',
                'background' => 'linear-gradient(135deg,#123B60,#267BB5)',
            ],
            'geografi' => [
                'icon' => '🌍',
                'label' => 'GEOGRAFI',
                'description' => 'Mempelajari bumi, ruang, dan interaksi lingkungan',
                'background' => 'linear-gradient(135deg,#123B60,#267BB5)',
            ],
            'sosiologi' => [
                'icon' => '👥',
                'label' => 'SOSIOLOGI',
                'description' => 'Memahami struktur sosial dan dinamika masyarakat',
                'background' => 'linear-gradient(135deg,#123B60,#267BB5)',
            ],
        ];

        $defaultTheme = [
            'icon' => '▤',
            'label' => 'KELAS AKADEMIK',
            'description' => 'Ruang belajar dan berkembang bersama',
            'background' => 'linear-gradient(135deg,#123B60,#267BB5)',
        ];
    @endphp

    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Playfair+Display:wght@500;600;700;800&display=swap');

        :root {
            --academy-navy: #123B60;
            --academy-blue: #267BB5;
            --academy-light: #E8F1F8;
            --academy-ivory: #FAF9F6;
            --academy-text: #263747;
            --academy-muted: #788797;
            --academy-border: #E7EDF2;
        }

        .academy-page {
            background: var(--academy-ivory);
            color: var(--academy-text);
            min-height: 100vh;
            padding: 30px 0 65px;
            font-family: 'DM Sans', sans-serif;
        }

        .academy-page * {
            box-sizing: border-box;
        }

        .academy-wrap {
            width: min(1440px, 100%);
            margin: 0 auto;
            padding: 0 24px;
        }

        .academy-serif {
            font-family: 'Playfair Display', serif;
        }

        .academy-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 14px;
            border-radius: 100px;
            background: #E6F0F7;
            color: #185B87;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .14em;
        }

        /* HERO */
        .academy-hero {
            position: relative;
            isolation: isolate;
            overflow: hidden;
            display: grid;
            grid-template-columns: 1.15fr .85fr;
            align-items: center;
            gap: 30px;
            min-height: 380px;
            padding: 44px;
            border: 1px solid #E4EAF0;
            border-radius: 28px;
            background: linear-gradient(115deg, #FAFAF8 0%, #EAF2F8 100%);
            box-shadow: 0 16px 50px rgba(18,59,96,.055);
        }

        .academy-hero::before {
            content: "";
            position: absolute;
            z-index: -1;
            width: 380px;
            height: 380px;
            right: -100px;
            top: -180px;
            border: 1px solid rgba(38,123,181,.16);
            border-radius: 50%;
            box-shadow:
                0 0 0 30px rgba(38,123,181,.035),
                0 0 0 65px rgba(38,123,181,.025);
        }

        .academy-hero-title {
            margin-top: 20px;
            color: var(--academy-navy);
            font-family: 'Playfair Display', serif;
            font-size: clamp(34px, 4.5vw, 54px);
            font-weight: 700;
            letter-spacing: -1.5px;
            line-height: 1.12;
        }

        .academy-hero-title span {
            color: var(--academy-blue);
        }

        .academy-hero-description {
            max-width: 580px;
            margin-top: 18px;
            color: #66788A;
            font-size: 13.5px;
            line-height: 1.9;
        }

        /* BUTTONS WITH DYNAMIC CLICK & ACTIVE STATES */
        .academy-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 12px 24px;
            border-radius: 100px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition: all .2s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            border: 1px solid transparent;
            outline: none;
            user-select: none;
        }

        .academy-button:active {
            transform: scale(0.95);
        }

        .academy-button-primary {
            color: #ffffff !important;
            background: #123B60 !important;
            box-shadow: 0 8px 22px rgba(18,59,96,.25);
            border-color: #0E2E4B !important;
        }

        .academy-button-primary:hover {
            color: #ffffff !important;
            background: #0B2B48 !important;
            box-shadow: 0 10px 28px rgba(18,59,96,.32);
        }

        .academy-button-secondary {
            color: #123B60 !important;
            background: #E4EDF3 !important;
            border-color: #D5E5F0 !important;
            box-shadow: 0 2px 8px rgba(18,59,96,.04);
        }

        .academy-button-secondary:hover {
            color: #0E2E4B !important;
            background: #D8E6EF !important;
            border-color: #C2D8E8 !important;
        }

        /* HERO VISUAL */
        .academy-visual {
            position: relative;
            min-height: 290px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .academy-visual-main {
            position: absolute;
            inset: 8px 12px;
            overflow: hidden;
            border-radius: 26px;
            background:
                linear-gradient(145deg, rgba(18,59,96,.85), rgba(38,123,181,.75)),
                linear-gradient(135deg, #123B60, #7AB6D9);
            box-shadow: 0 20px 40px rgba(18,59,96,.15);
        }

        .academy-visual-content {
            position: relative;
            z-index: 1;
            display: flex;
            height: 100%;
            min-height: 290px;
            flex-direction: column;
            justify-content: center;
            padding: 32px;
            color: #fff;
        }

        .academy-visual-symbol {
            font-family: 'Playfair Display', serif;
            font-size: clamp(65px, 8vw, 100px);
            font-weight: 700;
            line-height: 1;
            color: rgba(255,255,255,.94);
        }

        /* SECTION */
        .academy-section {
            margin-top: 48px;
        }

        .academy-section-heading {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 20px;
        }

        .academy-section-label {
            color: var(--academy-blue);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .17em;
            text-transform: uppercase;
        }

        .academy-section-title {
            margin-top: 5px;
            color: var(--academy-navy);
            font-family: 'Playfair Display', serif;
            font-size: 26px;
            font-weight: 700;
            line-height: 1.3;
        }

        .academy-section-description {
            margin-top: 5px;
            color: var(--academy-muted);
            font-size: 12px;
            line-height: 1.7;
        }

        /* SPLIT PORTAL HUB CARDS */
        .academy-hub-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px;
            margin-top: 26px;
        }

        @media (max-width: 1024px) {
            .academy-hub-grid {
                grid-template-columns: repeat(1, minmax(0, 1fr));
            }
        }

        .academy-hub-card {
            display: block;
            text-decoration: none;
            padding: 26px;
            border-radius: 24px;
            border: 2px solid var(--academy-border);
            background: white;
            box-shadow: 0 10px 30px rgba(18,59,96,.04);
            transition: all .25s ease;
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }

        .academy-hub-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 16px 36px rgba(18,59,96,.09);
        }

        .academy-hub-card:active {
            transform: scale(0.98);
        }

        /* STATISTICS */
        .academy-stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
        }

        .academy-stat {
            padding: 20px;
            border: 1px solid var(--academy-border);
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 7px 22px rgba(18,59,96,.03);
            transition: .22s ease;
        }

        .academy-stat:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px rgba(18,59,96,.07);
        }

        .academy-stat-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .academy-stat-icon {
            display: grid;
            width: 42px;
            height: 42px;
            place-items: center;
            border-radius: 13px;
            background: #E8F1F8;
            color: #267BB5;
            font-size: 19px;
        }

        .academy-stat-number {
            margin-top: 16px;
            color: var(--academy-navy);
            font-family: 'Playfair Display', serif;
            font-size: 34px;
            font-weight: 700;
        }

        .academy-stat-label {
            margin-top: 4px;
            color: #7A8997;
            font-size: 11px;
            font-weight: 600;
        }

        /* CLASS CARDS */
        .academy-class-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px;
        }

        .academy-class-card {
            display: block;
            text-decoration: none;
            overflow: hidden;
            border: 1px solid var(--academy-border);
            border-radius: 22px;
            background: #fff;
            transition: .25s ease;
            cursor: pointer;
        }

        .academy-class-card:hover {
            transform: translateY(-5px);
            border-color: #BBD7E9;
            box-shadow: 0 18px 36px rgba(18,59,96,.1);
        }

        .academy-class-card:active {
            transform: scale(0.98);
        }

        .academy-class-cover {
            position: relative;
            display: flex;
            min-height: 140px;
            align-items: center;
            justify-content: space-between;
            overflow: hidden;
            padding: 22px;
            color: white;
            background-color: #123B60;
        }

        .academy-class-cover:not(.has-image)::before {
            content: "";
            position: absolute;
            width: 155px;
            height: 155px;
            right: -35px;
            top: -85px;
            border: 1px solid rgba(255,255,255,.25);
            border-radius: 50%;
            box-shadow:
                0 0 0 22px rgba(255,255,255,.06),
                0 0 0 45px rgba(255,255,255,.035);
        }

        .academy-cover-image {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .academy-class-card:hover .academy-cover-image {
            transform: scale(1.08);
        }

        .academy-cover-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(8, 22, 40, 0.88) 0%, rgba(18, 59, 96, 0.72) 55%, rgba(18, 59, 96, 0.38) 100%);
            pointer-events: none;
        }

        .academy-class-cover-info {
            position: relative;
            z-index: 1;
        }

        .academy-class-cover-label {
            color: rgba(255,255,255,.85);
            font-size: 9px;
            font-weight: 800;
            letter-spacing: .17em;
        }

        .academy-class-cover-title {
            margin-top: 8px;
            font-family: 'Playfair Display', serif;
            font-size: 20px;
            font-weight: 700;
        }

        .academy-class-symbol {
            position: relative;
            z-index: 1;
            display: grid;
            width: 62px;
            height: 62px;
            flex-shrink: 0;
            place-items: center;
            border: 1px solid rgba(255,255,255,.35);
            border-radius: 18px;
            background: rgba(255,255,255,.16);
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            font-weight: 700;
            backdrop-filter: blur(8px);
        }

        .academy-class-body {
            padding: 20px;
        }

        .academy-class-name {
            color: #183B59;
            font-family: 'Playfair Display', serif;
            font-size: 19px;
            font-weight: 700;
        }

        .academy-class-meta {
            margin-top: 5px;
            color: #8995A0;
            font-size: 11px;
        }

        .academy-class-numbers {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
            margin-top: 18px;
            padding: 14px 0;
            border-top: 1px solid #EEF1F3;
            border-bottom: 1px solid #EEF1F3;
        }

        .academy-class-number {
            color: #193E5D;
            font-family: 'Playfair Display', serif;
            font-size: 20px;
            font-weight: 700;
        }

        .academy-class-number-label {
            margin-top: 3px;
            color: #8995A0;
            font-size: 10px;
        }

        .academy-class-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-top: 15px;
        }

        .academy-class-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #176A9E;
            font-size: 11px;
            font-weight: 800;
        }

        /* PANELS */
        .academy-panel {
            min-width: 0;
            padding: 24px;
            border: 1px solid var(--academy-border);
            border-radius: 22px;
            background: white;
            box-shadow: 0 7px 25px rgba(18,59,96,.035);
        }

        .academy-panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 18px;
        }

        .academy-panel-icon {
            display: grid;
            width: 42px;
            height: 42px;
            flex-shrink: 0;
            place-items: center;
            border-radius: 13px;
            background: #E8F1F8;
            color: #267BB5;
            font-size: 18px;
        }

        .academy-list-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            padding: 14px 0;
            border-bottom: 1px solid #EDF1F4;
        }

        .academy-list-row:last-child {
            border-bottom: 0;
        }

        .academy-list-title {
            overflow: hidden;
            color: #263F55;
            font-size: 12.5px;
            font-weight: 700;
            text-overflow: ellipsis;
        }

        .academy-list-subtitle {
            margin-top: 4px;
            color: #8A97A3;
            font-size: 10.5px;
        }

        .academy-empty {
            padding: 28px 15px;
            border-radius: 14px;
            background: #F6F9FB;
            color: #8493A0;
            font-size: 12px;
            text-align: center;
        }

        .academy-footer {
            margin-top: 55px;
            padding-top: 22px;
            border-top: 1px solid #E5EAEF;
            color: #9AA5AE;
            font-size: 10.5px;
            text-align: center;
            letter-spacing: .04em;
        }

        /* RESPONSIVE */
        @media(max-width: 1100px) {
            .academy-class-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
            .academy-hero {
                padding: 34px;
            }
        }

        @media(max-width: 800px) {
            .academy-hero {
                grid-template-columns: 1fr;
                gap: 24px;
            }
            .academy-hub-grid {
                grid-template-columns: 1fr;
            }
            .academy-stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media(max-width: 560px) {
            .academy-page {
                padding: 18px 0 40px;
            }
            .academy-wrap {
                padding: 0 14px;
            }
            .academy-hero {
                padding: 24px 18px;
                border-radius: 22px;
            }
            .academy-hero-title {
                font-size: 32px;
            }
            .academy-class-grid {
                grid-template-columns: 1fr;
            }
            .academy-stat-number {
                font-size: 28px;
            }
        }
    </style>

    <main class="academy-page">
        <div class="academy-wrap">

            {{-- ==========================================
                 HERO SECTION
            =========================================== --}}
            <section class="academy-hero">
                <div class="academy-hero-content">
                    <span class="academy-eyebrow">
                        ✦ SMAS METHODIST BANDA ACEH
                    </span>

                    <h1 class="academy-hero-title">
                        Menginspirasi Siswa,<br>
                        <span>Membangun Masa Depan.</span>
                    </h1>

                    <p class="academy-hero-description">
                        Selamat datang, {{ Auth::user()->name }}. Kelola materi pembelajaran,
                        tugas siswa, dan asesmen ujian digital secara terpadu melalui
                        Methodist Learning Space.
                    </p>

                    {{-- ==========================================
                         TOMBOL NAVIGASI LANGSUNG KE E-CLASS & E-EXAM
                    =========================================== --}}
                    <div class="mt-7 flex flex-wrap items-center gap-3">
                        {{-- TOMBOL E-CLASS: Langsung navigasi ke halaman E-Class --}}
                        <a href="{{ route('guru.classes.index') }}"
                           class="academy-button academy-button-primary shadow-lg"
                           title="Buka halaman E-Class">
                            <span class="text-sm">📚</span>
                            <span>Buka E-Class (Ruang Kelas)</span>
                            <span class="ml-1 font-bold">→</span>
                        </a>

                        {{-- TOMBOL E-EXAM: Langsung navigasi ke halaman E-Exam --}}
                        <a href="{{ route('guru.exams.index') }}"
                           class="academy-button academy-button-secondary"
                           title="Buka halaman E-Exam">
                            <span class="text-sm">📋</span>
                            <span>Buka E-Exam (Kelola Ujian)</span>
                            <span class="ml-1 font-bold">→</span>
                        </a>

                        {{-- TOMBOL ROSTER PELAJARAN --}}
                        <a href="{{ route('guru.roster') }}"
                           class="academy-button bg-white text-[#123B60] border-slate-200 hover:border-[#267BB5] hover:bg-slate-50 shadow-sm"
                           title="Buka Roster Pelajaran">
                            <span class="text-sm">📅</span>
                            <span>Roster Pelajaran</span>
                            <span class="ml-1 font-bold">↗</span>
                        </a>
                    </div>

                    <div class="mt-8 flex flex-wrap gap-7">
                        <div>
                            <p class="academy-serif text-3xl font-bold text-[#123B60]">
                                {{ $totalClasses }}
                            </p>
                            <p class="mt-1 text-xs text-slate-500 font-medium">
                                Kelas Diampu
                            </p>
                        </div>

                        <div class="h-12 w-px bg-slate-200"></div>

                        <div>
                            <p class="academy-serif text-3xl font-bold text-[#123B60]">
                                {{ $totalStudents }}
                            </p>
                            <p class="mt-1 text-xs text-slate-500 font-medium">
                                Siswa Terdaftar
                            </p>
                        </div>

                        <div class="h-12 w-px bg-slate-200"></div>

                        <div>
                            <p class="academy-serif text-3xl font-bold text-[#123B60]">
                                {{ $totalAssignments + $totalExams }}
                            </p>
                            <p class="mt-1 text-xs text-slate-500 font-medium">
                                Aktivitas Akademik
                            </p>
                        </div>
                    </div>
                </div>

                {{-- HERO VISUAL --}}
                <div class="academy-visual">
                    <div class="academy-visual-main">
                        <div class="academy-visual-content">
                            <p class="text-[10px] font-bold tracking-[.2em] text-sky-100 uppercase">
                                Methodist Learning Space
                            </p>

                            <div class="academy-visual-symbol">
                                M.
                            </div>

                            <p class="academy-serif mt-2 text-xl font-semibold">
                                Belajar. Tumbuh. Berprestasi.
                            </p>

                            <p class="mt-2 max-w-xs text-xs leading-6 text-sky-100">
                                Mewujudkan pendidikan bermutu dengan pembelajaran yang bermakna.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ==========================================
                 PISAHKAN E-CLASS & E-EXAM (HUB PORTAL)
            =========================================== --}}
            <section class="academy-hub-grid">
                {{-- CARD E-CLASS: Seluruh kartu langsung navigasi ke E-Class --}}
                <a href="{{ route('guru.classes.index') }}"
                   class="academy-hub-card group relative border-slate-200 hover:border-[#267BB5] hover:shadow-md">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-2xl text-[#123B60] transition group-hover:scale-105 group-hover:bg-[#123B60] group-hover:text-white">
                            📚
                        </div>
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-3 py-1 text-[11px] font-bold text-[#123B60] group-hover:bg-blue-100 transition">
                            Ke Halaman E-Class <span>→</span>
                        </span>
                    </div>

                    <h3 class="academy-serif mt-5 text-2xl font-bold text-[#123B60] group-hover:text-[#267BB5] transition">
                        E-Class (Ruang Belajar & Kelas)
                    </h3>

                    <p class="mt-2 text-xs leading-6 text-slate-500">
                        Kelola ruang kelas, materi pembelajaran, pemberian tugas, dan direktori siswa per kelas.
                    </p>

                    <div class="mt-6 flex flex-wrap items-center gap-4 border-t border-slate-100 pt-4 text-xs">
                        <div>
                            <span class="font-extrabold text-[#123B60]">{{ $totalClasses }}</span>
                            <span class="text-slate-500">Kelas Aktif</span>
                        </div>
                        <span class="text-slate-300">·</span>
                        <div>
                            <span class="font-extrabold text-[#123B60]">{{ $totalStudents }}</span>
                            <span class="text-slate-500">Siswa Bergabung</span>
                        </div>
                        <span class="text-slate-300">·</span>
                        <div>
                            <span class="font-extrabold text-[#123B60]">{{ $totalAssignments }}</span>
                            <span class="text-slate-500">Tugas Diberikan</span>
                        </div>
                    </div>
                </a>

                {{-- CARD E-EXAM: Seluruh kartu langsung navigasi ke E-Exam --}}
                <a href="{{ route('guru.exams.index') }}"
                   class="academy-hub-card group relative border-slate-200 hover:border-[#4338A8] hover:shadow-md">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-50 text-2xl text-[#4338A8] transition group-hover:scale-105 group-hover:bg-[#4338A8] group-hover:text-white">
                            📋
                        </div>
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-indigo-50 px-3 py-1 text-[11px] font-bold text-[#4338A8] group-hover:bg-indigo-100 transition">
                            Ke Halaman E-Exam <span>→</span>
                        </span>
                    </div>

                    <h3 class="academy-serif mt-5 text-2xl font-bold text-[#123B60] group-hover:text-[#4338A8] transition">
                        E-Exam (Ujian & Asesmen Digital)
                    </h3>

                    <p class="mt-2 text-xs leading-6 text-slate-500">
                        Buat dan jadwalkan ujian, atur bank soal, publikasi ujian, dan periksa jawaban esai siswa.
                    </p>

                    <div class="mt-6 flex flex-wrap items-center gap-4 border-t border-slate-100 pt-4 text-xs">
                        <div>
                            <span class="font-extrabold text-[#4338A8]">{{ $totalExams }}</span>
                            <span class="text-slate-500">Ujian Terdaftar</span>
                        </div>
                        <span class="text-slate-300">·</span>
                        <div>
                            <span class="font-extrabold {{ $totalUngraded > 0 ? 'text-amber-600' : 'text-slate-700' }}">{{ $totalUngraded }}</span>
                            <span class="text-slate-500">Esai Menunggu Koreksi</span>
                        </div>
                    </div>
                </a>

                {{-- CARD ROSTER: Seluruh kartu langsung navigasi ke Roster --}}
                <a href="{{ route('guru.roster') }}"
                   class="academy-hub-card group relative border-slate-200 hover:border-[#267BB5] hover:shadow-md">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-sky-50 text-2xl text-[#123B60] transition group-hover:scale-105 group-hover:bg-[#123B60] group-hover:text-white">
                            📅
                        </div>
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-sky-50 px-3 py-1 text-[11px] font-bold text-[#123B60] group-hover:bg-sky-100 transition">
                            Ke Roster Resmi <span>→</span>
                        </span>
                    </div>

                    <h3 class="academy-serif mt-5 text-2xl font-bold text-[#123B60] group-hover:text-[#267BB5] transition">
                        Roster &amp; Jadwal Mengajar
                    </h3>

                    <p class="mt-2 text-xs leading-6 text-slate-500">
                        Jadwal pelajaran resmi TP 2026-2027 seluruh kelas (X IPAS A s/d XII B), filter per hari, dan jadwal mengajar pribadi.
                    </p>

                    <div class="mt-6 flex flex-wrap items-center gap-4 border-t border-slate-100 pt-4 text-xs">
                        <div>
                            <span class="font-extrabold text-[#123B60]">6</span>
                            <span class="text-slate-500">Hari Pembelajaran</span>
                        </div>
                        <span class="text-slate-300">·</span>
                        <div>
                            <span class="font-extrabold text-[#123B60]">6</span>
                            <span class="text-slate-500">Kelas Terdaftar</span>
                        </div>
                        <span class="text-slate-300">·</span>
                        <div>
                            <span class="font-extrabold text-emerald-600">{{ $schedule->count() }}</span>
                            <span class="text-slate-500">Sesi Mengajar Anda</span>
                        </div>
                    </div>
                </a>
            </section>

            {{-- ==========================================
                 JADWAL TATAP MUKA ANDA (DARI ROSTER RESMI)
            =========================================== --}}
            <section class="academy-section">
                <div class="academy-section-heading">
                    <div>
                        <p class="academy-section-label">Jadwal Mengajar Terjadwal</p>
                        <h2 class="academy-section-title">
                            Jadwal Tatap Muka Anda
                        </h2>
                        <p class="academy-section-description">
                            Jadwal pembelajaran resmi berdasarkan Roster Pelajaran Kurikulum TP 2026-2027.
                        </p>
                    </div>

                    <a href="{{ route('guru.roster') }}"
                       class="academy-button bg-white text-[#123B60] border-slate-200 hover:border-[#267BB5] text-xs py-2 px-4 shadow-sm">
                        <span>Lihat Roster Lengkap Sekolah</span>
                        <span class="font-bold">↗</span>
                    </a>
                </div>

                @if ($schedule->isEmpty())
                    <div class="rounded-2xl border border-slate-200 bg-white p-8 text-center text-slate-400">
                        <p class="font-bold text-slate-600">Belum ada jadwal tatap muka yang dijadwalkan.</p>
                        <a href="{{ route('guru.roster') }}" class="mt-2 inline-block text-xs font-bold text-[#267BB5] underline">
                            Buka Roster Sekolah untuk melihat pembagian jam pelajaran
                        </a>
                    </div>
                @else
                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        @php
                            $groupedSchedules = $schedule->groupBy('day');
                            $dayOrder = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];
                        @endphp
                        @foreach ($dayOrder as $d)
                            @if ($groupedSchedules->has($d))
                                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-xs hover:border-[#267BB5] transition">
                                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                        <span class="font-black text-xs uppercase tracking-wider text-[#123B60] flex items-center gap-1.5">
                                            <span>📅</span>
                                            <span>{{ ucfirst($d) }}</span>
                                        </span>
                                        <span class="rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-bold text-[#123B60]">
                                            {{ $groupedSchedules[$d]->count() }} Sesi
                                        </span>
                                    </div>

                                    <div class="mt-3 space-y-2.5">
                                        @foreach ($groupedSchedules[$d] as $item)
                                            <div class="rounded-xl bg-slate-50 p-2.5 text-xs border border-slate-100">
                                                <div class="flex items-center justify-between">
                                                    <span class="font-black text-slate-800">{{ $item->class_name }}</span>
                                                    <span class="rounded bg-[#123B60] px-1.5 py-0.5 text-[9px] font-bold text-white">
                                                        {{ $item->subject_name }}
                                                    </span>
                                                </div>
                                                <p class="mt-1 font-semibold text-slate-500 text-[11px]">
                                                    ⏰ {{ substr($item->start_time, 0, 5) }} - {{ substr($item->end_time, 0, 5) }} WIB
                                                </p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </section>

            {{-- ==========================================
                 STATISTIK RINGKAS
            =========================================== --}}
            <section class="academy-section">
                <div class="academy-section-heading">
                    <div>
                        <p class="academy-section-label">Ringkasan Aktivitas</p>
                        <h2 class="academy-section-title">
                            Aktivitas Akademik Anda
                        </h2>
                        <p class="academy-section-description">
                            Gambaran umum data kelas, siswa, tugas, dan ujian Anda.
                        </p>
                    </div>
                </div>

                <div class="academy-stats">
                    <a href="{{ route('guru.classes.index') }}" class="academy-stat cursor-pointer hover:border-[#267BB5] hover:ring-2 hover:ring-blue-100 transition">
                        <div class="academy-stat-top">
                            <div class="academy-stat-icon">▤</div>
                            <span class="text-[10px] font-bold tracking-wider text-blue-600">
                                E-CLASS
                            </span>
                        </div>
                        <p class="academy-stat-number">{{ $totalClasses }}</p>
                        <p class="academy-stat-label">Kelas yang Anda Ampu</p>
                    </a>

                    <a href="{{ route('guru.classes.index') }}" class="academy-stat cursor-pointer hover:border-[#267BB5] hover:ring-2 hover:ring-blue-100 transition">
                        <div class="academy-stat-top">
                            <div class="academy-stat-icon">♙</div>
                            <span class="text-[10px] font-bold tracking-wider text-blue-600">
                                SISWA
                            </span>
                        </div>
                        <p class="academy-stat-number">{{ $totalStudents }}</p>
                        <p class="academy-stat-label">Total Siswa Terdaftar</p>
                    </a>

                    <a href="{{ route('guru.classes.index') }}" class="academy-stat cursor-pointer hover:border-[#267BB5] hover:ring-2 hover:ring-blue-100 transition">
                        <div class="academy-stat-top">
                            <div class="academy-stat-icon">✎</div>
                            <span class="text-[10px] font-bold tracking-wider text-blue-600">
                                TUGAS
                            </span>
                        </div>
                        <p class="academy-stat-number">{{ $totalAssignments }}</p>
                        <p class="academy-stat-label">Tugas Diberikan</p>
                    </a>

                    <a href="{{ route('guru.exams.index') }}" class="academy-stat cursor-pointer hover:border-[#4338A8] hover:ring-2 hover:ring-indigo-100 transition">
                        <div class="academy-stat-top">
                            <div class="academy-stat-icon">✓</div>
                            <span class="text-[10px] font-bold tracking-wider text-indigo-600">
                                E-EXAM
                            </span>
                        </div>
                        <p class="academy-stat-number">{{ $totalExams }}</p>
                        <p class="academy-stat-label">Ujian Dibuat</p>
                    </a>
                </div>
            </section>

            {{-- ==========================================
                 KONTEN E-CLASS
            =========================================== --}}
            <div class="space-y-12">

                {{-- KELAS YANG ANDA AMPU --}}
                <section class="academy-section">
                    <div class="academy-section-heading">
                        <div>
                            <p class="academy-section-label">E-Class Guru</p>
                            <h2 class="academy-section-title">
                                Kelas yang Anda Ampu
                            </h2>
                            <p class="academy-section-description">
                                Klik pada salah satu kartu kelas untuk membuka ruang kelas, materi, jadwal, tugas, dan daftar siswa.
                            </p>
                        </div>

                        <a href="{{ route('guru.classes.index') }}"
                           class="text-xs font-bold text-blue-700 hover:text-blue-900 transition">
                            Lihat Semua Kelas E-Class →
                        </a>
                    </div>

                    <div class="academy-class-grid">
                        @forelse($overview as $class)
                            @php
                                $subjectLower = strtolower($class->subject_name ?? '');
                                $theme = $defaultTheme;

                                foreach ($subjectThemes as $key => $value) {
                                    if (str_contains($subjectLower, $key)) {
                                        $theme = $value;
                                        if (empty($theme['image']) && file_exists(public_path('images/subjects/' . $key . '.jpg'))) {
                                            $theme['image'] = asset('images/subjects/' . $key . '.jpg');
                                        }
                                        break;
                                    }
                                }
                            @endphp

                            {{-- KARTU KELAS: LANGSUNG BISA DIKLIK --}}
                            <a href="{{ route('guru.classes.show', $class->cst_id) }}"
                               class="academy-class-card group"
                               title="Klik untuk membuka kelas {{ $class->class_name }} {{ $class->subject_name }}">

                                <div class="academy-class-cover {{ !empty($theme['image']) ? 'has-image' : '' }}"
                                     style="{{ empty($theme['image']) ? 'background: ' . $theme['background'] : '' }}">

                                    @if(!empty($theme['image']))
                                        <img src="{{ $theme['image'] }}"
                                             alt="{{ $class->subject_name }}"
                                             class="academy-cover-image">
                                        <div class="academy-cover-overlay"></div>
                                    @endif

                                    <div class="academy-class-cover-info">
                                        <p class="academy-class-cover-label">
                                            {{ $theme['label'] }}
                                        </p>
                                        <h3 class="academy-class-cover-title">
                                            {{ $class->subject_name }}
                                        </h3>
                                        <p class="mt-1 text-[10px] text-white/90">
                                            {{ $theme['description'] }}
                                        </p>
                                    </div>

                                    <div class="academy-class-symbol group-hover:scale-110 transition duration-300">
                                        {{ $theme['icon'] }}
                                    </div>
                                </div>

                                <div class="academy-class-body">
                                    <p class="text-[9px] font-bold uppercase tracking-[.15em] text-slate-400">
                                        KELAS
                                    </p>

                                    <h3 class="academy-class-name group-hover:text-[#267BB5] transition">
                                        {{ $class->class_name }}
                                    </h3>

                                    <p class="academy-class-meta">
                                        {{ $class->student_count }} siswa terdaftar
                                    </p>

                                    <div class="academy-class-numbers">
                                        <div>
                                            <p class="academy-class-number">
                                                {{ $class->student_count }}
                                            </p>
                                            <p class="academy-class-number-label">
                                                Siswa
                                            </p>
                                        </div>

                                        <div>
                                            <p class="academy-class-number">
                                                {{ $class->assignment_count }}
                                            </p>
                                            <p class="academy-class-number-label">
                                                Tugas
                                            </p>
                                        </div>

                                        <div>
                                            <p class="academy-class-number">
                                                {{ $class->exam_count }}
                                            </p>
                                            <p class="academy-class-number-label">
                                                Ujian
                                            </p>
                                        </div>
                                    </div>

                                    <div class="academy-class-footer">
                                        <span class="text-[10px] text-slate-400">
                                            {{ $class->published_exam_count }} ujian aktif
                                        </span>

                                        <span class="academy-class-link group-hover:translate-x-1 transition">
                                            Buka Kelas <span>→</span>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="col-span-full rounded-2xl border border-dashed border-blue-200 bg-white px-6 py-14 text-center">
                                <div class="mx-auto grid h-16 w-16 place-items-center rounded-full bg-blue-50 text-3xl text-blue-600">
                                    ▤
                                </div>
                                <h3 class="academy-serif mt-5 text-2xl font-bold text-[#123B60]">
                                    Belum Ada Kelas yang Diampu
                                </h3>
                                <p class="mx-auto mt-3 max-w-md text-sm leading-7 text-slate-500">
                                    Kelas yang ditugaskan kepada Anda akan otomatis muncul di bagian ini.
                                </p>
                                <a href="{{ route('guru.classes.index') }}"
                                   class="academy-button academy-button-primary mt-5">
                                    Ke Halaman E-Class →
                                </a>
                            </div>
                        @endforelse
                    </div>
                </section>

                {{-- TINJAUAN TUGAS DENGAN TOMBOL LANGSUNG --}}
                <section class="academy-section">
                    <div class="academy-panel">
                        <div class="academy-panel-header">
                            <div>
                                <p class="academy-section-label">
                                    Asesmen Tugas
                                </p>
                                <h3 class="academy-section-title text-xl">
                                    Tinjauan Tugas & Pengumpulan
                                </h3>
                                <p class="mt-1 text-xs text-slate-400">
                                    Pantau status pengumpulan dan penilaian tugas siswa.
                                </p>
                            </div>

                            <div class="academy-panel-icon">
                                ✎
                            </div>
                        </div>

                        <div class="divide-y divide-slate-100">
                            @forelse($assignmentStatuses as $assignment)
                                <div class="academy-list-row flex-col items-start gap-3 sm:flex-row sm:items-center sm:justify-between py-4">
                                    <div class="min-w-0 flex-1">
                                        <a href="{{ route('guru.classes.assignments.show', $assignment->assignment_id) }}"
                                           class="academy-list-title block hover:text-[#267BB5] transition text-sm">
                                            {{ $assignment->title }}
                                        </a>

                                        <p class="academy-list-subtitle mt-1">
                                            {{ $assignment->class_name }} · {{ $assignment->subject_name }}
                                            @if($assignment->due_date)
                                                · Batas: {{ \Carbon\Carbon::parse($assignment->due_date)->format('d M Y, H:i') }} WIB
                                            @endif
                                        </p>
                                    </div>

                                    <div class="flex items-center gap-4 self-end sm:self-center shrink-0">
                                        <div class="text-right">
                                            <span class="text-sm font-extrabold text-[#1C6596]">
                                                {{ $assignment->graded_count }}/{{ $assignment->submitted_count }}
                                            </span>
                                            <p class="text-[9px] font-medium text-slate-400">
                                                Dinilai
                                            </p>
                                        </div>

                                        {{-- TOMBOL LINK LANGSUNG KE TUGAS TERSEBUT --}}
                                        <a href="{{ route('guru.classes.assignments.show', $assignment->assignment_id) }}"
                                           class="inline-flex items-center gap-1.5 rounded-xl bg-blue-50 px-3.5 py-2 text-xs font-bold text-blue-700 hover:bg-blue-100 hover:text-blue-900 transition"
                                           title="Buka detail dan periksa tugas ini">
                                            <span>Periksa</span>
                                            <span>→</span>
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="academy-empty">
                                    Belum ada tugas yang dibuat.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </section>

            </div>

            {{-- ==========================================
                 KONTEN E-EXAM
            =========================================== --}}
            <div class="space-y-12">

                {{-- DAFTAR UJIAN E-EXAM --}}
                <section class="academy-section">
                    <div class="academy-section-heading">
                        <div>
                            <p class="academy-section-label">Asesmen Digital</p>
                            <h2 class="academy-section-title">
                                E-Exam: Ujian & Kuis Online
                            </h2>
                            <p class="academy-section-description">
                                Kelola bank soal, jadwal pelaksanaan ujian, dan koreksi hasil asesmen siswa.
                            </p>
                        </div>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('guru.exams.create') }}"
                               class="inline-flex items-center gap-1.5 rounded-full bg-[#123B60] px-4 py-2 text-xs font-bold text-white shadow-sm hover:bg-[#0B2B48] transition">
                                <span>＋</span>
                                <span>Tambah Ujian Baru</span>
                            </a>

                            <a href="{{ route('guru.exams.index') }}"
                               class="text-xs font-bold text-indigo-700 hover:text-indigo-900 transition">
                                Buka Halaman E-Exam →
                            </a>
                        </div>
                    </div>

                    @if($totalUngraded > 0)
                        <div class="mb-6 flex items-center justify-between gap-4 rounded-2xl border border-amber-200 bg-amber-50 p-5 shadow-sm">
                            <div class="flex items-center gap-3">
                                <span class="text-2xl">⏳</span>
                                <div>
                                    <p class="text-sm font-bold text-amber-900">
                                        {{ $totalUngraded }} Jawaban Esai Siswa Menunggu Koreksi
                                    </p>
                                    <p class="text-xs text-amber-700 mt-0.5">
                                        Terdapat esai ujian siswa yang belum dinilai. Segera periksa hasil ujian untuk memperbarui nilai rapor.
                                    </p>
                                </div>
                            </div>
                            <a href="{{ route('guru.exams.index') }}"
                               class="inline-flex shrink-0 items-center rounded-xl bg-amber-600 px-4 py-2 text-xs font-bold text-white hover:bg-amber-700 transition shadow-sm">
                                Periksa Esai Sekarang →
                            </a>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                        @forelse($allExams as $exam)
                            <article class="flex flex-col justify-between rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-indigo-300 hover:shadow-md">
                                <div>
                                    <div class="flex items-start justify-between gap-3">
                                        <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $exam->is_published ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                                            {{ $exam->is_published ? 'Dipublikasikan' : 'Draf' }}
                                        </span>

                                        <span class="text-[11px] font-bold uppercase tracking-wider text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-md">
                                            {{ $exam->exam_type }}
                                        </span>
                                    </div>

                                    <h4 class="mt-3 text-base font-bold text-[#123B60]">
                                        {{ $exam->title }}
                                    </h4>

                                    <p class="mt-1 text-xs text-slate-500 font-medium">
                                        {{ $exam->classSubjectTeacher->schoolClass->name ?? 'Kelas' }} ·
                                        {{ $exam->classSubjectTeacher->subject->name ?? 'Mata Pelajaran' }}
                                    </p>

                                    <div class="mt-4 grid grid-cols-3 gap-2 border-t border-b border-slate-100 py-3 text-center text-xs">
                                        <div>
                                            <p class="text-[10px] text-slate-400">Soal</p>
                                            <p class="mt-0.5 font-bold text-slate-800">{{ $exam->questions_count }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] text-slate-400">Durasi</p>
                                            <p class="mt-0.5 font-bold text-slate-800">{{ $exam->duration_minutes }}m</p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] text-slate-400">KKM</p>
                                            <p class="mt-0.5 font-bold text-slate-800">{{ $exam->pass_score }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-5 flex items-center justify-between gap-2 pt-2">
                                    <a href="{{ route('guru.exams.results', $exam) }}"
                                       class="text-xs font-bold text-emerald-700 hover:text-emerald-900 transition">
                                        Periksa Hasil →
                                    </a>

                                    <a href="{{ route('guru.exams.show', $exam) }}"
                                       class="inline-flex items-center gap-1 rounded-xl bg-indigo-50 px-3 py-1.5 text-xs font-bold text-[#4338A8] hover:bg-indigo-100 transition">
                                        <span>Kelola Soal</span>
                                        <span>→</span>
                                    </a>
                                </div>
                            </article>
                        @empty
                            <div class="col-span-full rounded-2xl border border-dashed border-indigo-200 bg-white p-12 text-center">
                                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-50 text-2xl text-indigo-600 mb-4">
                                    📋
                                </div>
                                <h3 class="text-base font-bold text-slate-800">Belum Ada Ujian Terdaftar</h3>
                                <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
                                    Buat ujian atau kuis online pertama Anda untuk mengevaluasi pemahaman siswa.
                                </p>
                                <a href="{{ route('guru.exams.create') }}"
                                   class="mt-4 inline-flex items-center gap-1.5 rounded-full bg-[#123B60] px-5 py-2.5 text-xs font-bold text-white hover:bg-[#0B2B48] transition">
                                    <span>＋</span>
                                    <span>Buat Ujian Baru</span>
                                </a>
                            </div>
                        @endforelse
                    </div>
                </section>

            </div>

        </div>
    </main>
</x-app-layout>
