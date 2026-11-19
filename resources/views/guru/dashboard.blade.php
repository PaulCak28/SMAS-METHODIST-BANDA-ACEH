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
                'background' => 'linear-gradient(135deg,#164E86,#4299D5)',
            ],
            'bahasa indonesia' => [
                'icon' => 'Aa',
                'label' => 'BAHASA & SASTRA INDONESIA',
                'description' => 'Mengembangkan literasi dan kekuatan berbahasa',
                'background' => 'linear-gradient(135deg,#9F3447,#E87986)',
            ],
            'bahasa inggris' => [
                'icon' => 'Ab',
                'label' => 'BAHASA INGGRIS',
                'description' => 'Berkomunikasi dan terhubung secara global',
                'background' => 'linear-gradient(135deg,#4338A8,#818CF8)',
            ],
            'biologi' => [
                'icon' => '✿',
                'label' => 'BIOLOGI',
                'description' => 'Menjelajahi dunia kehidupan dan ekosistem',
                'background' => 'linear-gradient(135deg,#176B50,#4DBB8B)',
            ],
            'fisika' => [
                'icon' => '⚛',
                'label' => 'FISIKA',
                'description' => 'Memahami hukum alam dan prinsip semesta',
                'background' => 'linear-gradient(135deg,#5B3C99,#A78BFA)',
            ],
            'kimia' => [
                'icon' => '⚗',
                'label' => 'KIMIA',
                'description' => 'Mempelajari materi, zat, dan reaksi kimia',
                'background' => 'linear-gradient(135deg,#A45D14,#F2B84B)',
            ],
            'sejarah' => [
                'icon' => '◷',
                'label' => 'SEJARAH',
                'description' => 'Mengambil hikmah dan perjalanan masa lalu',
                'background' => 'linear-gradient(135deg,#9A4B21,#E99A61)',
            ],
            'informatika' => [
                'icon' => '</>',
                'label' => 'INFORMATIKA',
                'description' => 'Membangun logika digital dan komputasi',
                'background' => 'linear-gradient(135deg,#08677F,#36B7D2)',
            ],
            'agama' => [
                'icon' => '✧',
                'label' => 'PENDIDIKAN AGAMA',
                'description' => 'Membina karakter, iman, dan pekerti mulia',
                'background' => 'linear-gradient(135deg,#72502D,#C49B65)',
            ],
            'ekonomi' => [
                'icon' => '↗',
                'label' => 'EKONOMI',
                'description' => 'Memahami pasar, bisnis, dan keuangan',
                'background' => 'linear-gradient(135deg,#126C78,#4BB7BD)',
            ],
            'geografi' => [
                'icon' => '🌍',
                'label' => 'GEOGRAFI',
                'description' => 'Mempelajari bumi, ruang, dan interaksi lingkungan',
                'background' => 'linear-gradient(135deg,#1b6e53,#56ab7c)',
            ],
            'sosiologi' => [
                'icon' => '👥',
                'label' => 'SOSIOLOGI',
                'description' => 'Memahami struktur sosial dan dinamika masyarakat',
                'background' => 'linear-gradient(135deg,#843b62,#d46a92)',
            ],
        ];

        $defaultTheme = [
            'icon' => '▤',
            'label' => 'KELAS AKADEMIK',
            'description' => 'Ruang belajar dan berkembang bersama',
            'background' => 'linear-gradient(135deg,#123B60,#4299D5)',
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

        .academy-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 12px 22px;
            border-radius: 100px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition: .22s ease;
        }

        .academy-button:hover {
            transform: translateY(-2px);
        }

        .academy-button-primary {
            color: #fff;
            background: var(--academy-navy);
            box-shadow: 0 6px 18px rgba(18,59,96,.18);
        }

        .academy-button-primary:hover {
            color: #fff;
            background: #0B2B48;
        }

        .academy-button-secondary {
            color: var(--academy-navy);
            background: #E4EDF3;
        }

        .academy-button-secondary:hover {
            color: var(--academy-navy);
            background: #D5E5F0;
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
            margin-top: 50px;
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

        /* SPLIT PORTAL HUB CARDS (E-CLASS & E-EXAM) */
        .academy-hub-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
            margin-top: 28px;
        }

        .academy-hub-card {
            display: block;
            text-decoration: none;
            padding: 28px;
            border-radius: 24px;
            border: 1px solid var(--academy-border);
            background: white;
            box-shadow: 0 10px 30px rgba(18,59,96,.04);
            transition: all .25s ease;
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }

        .academy-hub-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 18px 40px rgba(18,59,96,.1);
            border-color: #B5D2E8;
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

        /* CLASS CARDS (DIRECTLY CLICKABLE) */
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

        .academy-class-cover {
            position: relative;
            display: flex;
            min-height: 140px;
            align-items: center;
            justify-content: space-between;
            overflow: hidden;
            padding: 22px;
            color: white;
        }

        .academy-class-cover::before {
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

                    <div class="mt-7 flex flex-wrap gap-3">
                        <a href="{{ route('guru.classes.index') }}"
                           class="academy-button academy-button-primary">
                            <span>📚</span>
                            Buka E-Class (Ruang Kelas)
                            <span>→</span>
                        </a>

                        <a href="{{ route('guru.exams.index') }}"
                           class="academy-button academy-button-secondary">
                            <span>📋</span>
                            Buka E-Exam (Kelola Ujian)
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
                 PISAHKAN E-CLASS & E-EXAM (HUB UTAMA - CARD BISA DIKLIK)
            =========================================== --}}
            <section class="academy-hub-grid">
                {{-- CARD E-CLASS --}}
                <a href="{{ route('guru.classes.index') }}"
                   class="academy-hub-card group hover:border-[#267BB5] relative">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-2xl text-[#123B60] transition group-hover:scale-105 group-hover:bg-[#123B60] group-hover:text-white">
                            📚
                        </div>
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-3 py-1 text-[11px] font-bold text-[#123B60]">
                            Halaman E-Class <span>→</span>
                        </span>
                    </div>

                    <h3 class="academy-serif mt-5 text-2xl font-bold text-[#123B60] group-hover:text-[#267BB5] transition">
                        E-Class (Ruang Belajar & Kelas)
                    </h3>

                    <p class="mt-2 text-xs leading-6 text-slate-500">
                        Kelola ruang kelas, unggah materi pembelajaran, berikan tugas, dan pantau direktori siswa di masing-masing kelas yang Anda ampu.
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

                {{-- CARD E-EXAM --}}
                <a href="{{ route('guru.exams.index') }}"
                   class="academy-hub-card group hover:border-[#4338A8] relative">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-50 text-2xl text-[#4338A8] transition group-hover:scale-105 group-hover:bg-[#4338A8] group-hover:text-white">
                            📋
                        </div>
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-indigo-50 px-3 py-1 text-[11px] font-bold text-[#4338A8]">
                            Halaman E-Exam <span>→</span>
                        </span>
                    </div>

                    <h3 class="academy-serif mt-5 text-2xl font-bold text-[#123B60] group-hover:text-[#4338A8] transition">
                        E-Exam (Ujian & Asesmen Digital)
                    </h3>

                    <p class="mt-2 text-xs leading-6 text-slate-500">
                        Buat dan jadwalkan ujian, atur bank soal pilihan ganda maupun esai, publikasi ujian, serta lakukan koreksi jawaban siswa.
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
                    <article class="academy-stat">
                        <div class="academy-stat-top">
                            <div class="academy-stat-icon">▤</div>
                            <span class="text-[10px] font-bold tracking-wider text-blue-600">
                                E-CLASS
                            </span>
                        </div>
                        <p class="academy-stat-number">{{ $totalClasses }}</p>
                        <p class="academy-stat-label">Kelas yang Anda Ampu</p>
                    </article>

                    <article class="academy-stat">
                        <div class="academy-stat-top">
                            <div class="academy-stat-icon">♙</div>
                            <span class="text-[10px] font-bold tracking-wider text-blue-600">
                                SISWA
                            </span>
                        </div>
                        <p class="academy-stat-number">{{ $totalStudents }}</p>
                        <p class="academy-stat-label">Total Siswa Terdaftar</p>
                    </article>

                    <article class="academy-stat">
                        <div class="academy-stat-top">
                            <div class="academy-stat-icon">✎</div>
                            <span class="text-[10px] font-bold tracking-wider text-blue-600">
                                TUGAS
                            </span>
                        </div>
                        <p class="academy-stat-number">{{ $totalAssignments }}</p>
                        <p class="academy-stat-label">Tugas Diberikan</p>
                    </article>

                    <article class="academy-stat">
                        <div class="academy-stat-top">
                            <div class="academy-stat-icon">✓</div>
                            <span class="text-[10px] font-bold tracking-wider text-blue-600">
                                E-EXAM
                            </span>
                        </div>
                        <p class="academy-stat-number">{{ $totalExams }}</p>
                        <p class="academy-stat-label">Ujian Dibuat</p>
                    </article>
                </div>
            </section>

            {{-- ==========================================
                 BAGIAN E-CLASS: KELAS YANG ANDA AMPU (CARD BISA DIKLIK)
            =========================================== --}}
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
                                    break;
                                }
                            }
                        @endphp

                        {{-- KARTU KELAS: LANGSUNG BISA DIKLIK --}}
                        <a href="{{ route('guru.classes.show', $class->cst_id) }}"
                           class="academy-class-card group"
                           title="Klik untuk membuka kelas {{ $class->class_name }} {{ $class->subject_name }}">

                            <div class="academy-class-cover"
                                 style="background: {{ $theme['background'] }}">
                                <div class="academy-class-cover-info">
                                    <p class="academy-class-cover-label">
                                        {{ $theme['label'] }}
                                    </p>
                                    <h3 class="academy-class-cover-title">
                                        {{ $class->subject_name }}
                                    </h3>
                                    <p class="mt-1 text-[10px] text-white/80">
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

            {{-- ==========================================
                 BAGIAN TINJAUAN TUGAS (DILENGKAPI TOMBOL KE TUGAS) & E-EXAM RECENT
            =========================================== --}}
            <section class="academy-section grid grid-cols-1 gap-6 lg:grid-cols-2">

                {{-- TINJAUAN TUGAS (DENGAN TOMBOL LANGSUNG KE TUGAS) --}}
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

                    @forelse($assignmentStatuses as $assignment)
                        <div class="academy-list-row flex-col items-start gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div class="min-w-0 flex-1">
                                <a href="{{ route('guru.classes.assignments.show', $assignment->assignment_id) }}"
                                   class="academy-list-title block hover:text-[#267BB5] transition">
                                    {{ $assignment->title }}
                                </a>

                                <p class="academy-list-subtitle">
                                    {{ $assignment->class_name }} · {{ $assignment->subject_name }}
                                    @if($assignment->due_date)
                                        · Tenggat: {{ \Carbon\Carbon::parse($assignment->due_date)->format('d M Y, H:i') }}
                                    @endif
                                </p>
                            </div>

                            <div class="flex items-center gap-3 self-end sm:self-center shrink-0">
                                <div class="text-right">
                                    <span class="text-xs font-extrabold text-[#1C6596]">
                                        {{ $assignment->graded_count }}/{{ $assignment->submitted_count }}
                                    </span>
                                    <p class="text-[9px] font-medium text-slate-400">
                                        Dinilai
                                    </p>
                                </div>

                                {{-- TOMBOL YANG ME-LINK-KAN LANGSUNG KE TUGAS TERSEBUT --}}
                                <a href="{{ route('guru.classes.assignments.show', $assignment->assignment_id) }}"
                                   class="inline-flex items-center gap-1 rounded-xl bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-700 hover:bg-blue-100 hover:text-blue-900 transition"
                                   title="Buka detail dan penilaian tugas ini">
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

                    @if($totalUngraded > 0)
                        <div class="mt-5 flex items-center gap-3 rounded-xl border border-amber-100 bg-amber-50 p-4">
                            <span class="text-xl">⏳</span>
                            <div>
                                <p class="text-xs font-bold text-amber-800">
                                    {{ $totalUngraded }} esai ujian menunggu penilaian
                                </p>
                                <p class="mt-1 text-[10px] text-amber-700">
                                    Terdapat jawaban esai siswa pada ujian yang belum diperiksa.
                                </p>
                            </div>
                            <a href="{{ route('guru.exams.index') }}"
                               class="ml-auto inline-flex shrink-0 items-center rounded-lg bg-amber-600 px-3 py-1.5 text-xs font-bold text-white hover:bg-amber-700 transition">
                                Periksa Esai
                            </a>
                        </div>
                    @endif
                </div>

                {{-- BAGIAN E-EXAM: UJIAN & ASESMEN TERBARU --}}
                <div class="academy-panel">
                    <div class="academy-panel-header">
                        <div>
                            <p class="academy-section-label">
                                Asesmen Digital
                            </p>
                            <h3 class="academy-section-title text-xl">
                                E-Exam: Ujian Terbaru
                            </h3>
                            <p class="mt-1 text-xs text-slate-400">
                                Daftar ujian dan kuis yang tersedia untuk kelas Anda.
                            </p>
                        </div>

                        <div class="academy-panel-icon">
                            📋
                        </div>
                    </div>

                    @forelse($recentExams as $exam)
                        <div class="academy-list-row flex-col items-start gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div class="min-w-0 flex-1">
                                <a href="{{ route('guru.exams.show', $exam) }}"
                                   class="academy-list-title block hover:text-[#4338A8] transition">
                                    {{ $exam->title }}
                                </a>

                                <p class="academy-list-subtitle">
                                    {{ $exam->classSubjectTeacher->schoolClass->name ?? 'Kelas' }} ·
                                    {{ $exam->classSubjectTeacher->subject->name ?? 'Mapel' }} ·
                                    {{ $exam->questions_count }} Soal
                                </p>
                            </div>

                            <div class="flex items-center gap-2 self-end sm:self-center shrink-0">
                                @if($exam->is_published)
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-bold text-emerald-700">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-[10px] font-bold text-slate-600">
                                        Draf
                                    </span>
                                @endif

                                <a href="{{ route('guru.exams.show', $exam) }}"
                                   class="inline-flex items-center gap-1 rounded-xl bg-indigo-50 px-3 py-1.5 text-xs font-bold text-[#4338A8] hover:bg-indigo-100 transition"
                                   title="Kelola soal dan sesi ujian ini">
                                    <span>Kelola</span>
                                    <span>→</span>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="academy-empty">
                            Belum ada ujian yang dibuat.
                        </div>
                    @endforelse

                    <div class="mt-5 border-t border-slate-100 pt-4 flex items-center justify-between">
                        <span class="text-xs text-slate-500">
                            Kelola bank soal dan hasil ujian
                        </span>
                        <a href="{{ route('guru.exams.index') }}"
                           class="inline-flex items-center gap-1.5 text-xs font-bold text-[#267BB5] hover:text-[#123B60] transition">
                            Buka Semua Ujian di E-Exam →
                        </a>
                    </div>
                </div>

            </section>

            {{-- ==========================================
                 FOOTER
            =========================================== --}}
            <footer class="academy-footer">
                <p class="academy-serif text-base font-bold text-[#123B60]">
                    Methodist Learning Space
                </p>

                <p class="mt-2 text-slate-500">
                    MENGINSPIRASI MINAT · MENGEMBANGKAN POTENSI · MEMBANGUN PRESTASI
                </p>

                <p class="mt-2 text-slate-400">
                    © {{ date('Y') }} SMAS Methodist Banda Aceh. Seluruh hak cipta dilindungi.
                </p>
            </footer>

        </div>
    </main>

</x-app-layout>
