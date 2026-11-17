
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[.2em] text-blue-600">
                    Methodist Learning Space
                </p>
                <h2 class="text-xl font-bold text-slate-800">
                    Teacher Dashboard
                </h2>
            </div>

            <div class="mt-2 inline-flex w-fit items-center gap-2 rounded-full border border-blue-100 bg-blue-50 px-4 py-2 text-xs font-semibold text-blue-800 sm:mt-0">
                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                Teacher Workspace
            </div>
        </div>
    </x-slot>

    @php
        /*
        |--------------------------------------------------------------------------
        | DASHBOARD STATISTICS
        |--------------------------------------------------------------------------
        */

        $totalClasses = $overview->count();
        $totalStudents = $overview->sum('student_count');
        $totalAssignments = $overview->sum('assignment_count');
        $totalExams = $overview->sum('exam_count');
        $totalUngraded = $overview->sum('ungraded_essays');

        /*
        |--------------------------------------------------------------------------
        | SUBJECT VISUAL THEMES
        |--------------------------------------------------------------------------
        */

        $subjectThemes = [
            'matematika' => [
                'icon' => '∑',
                'label' => 'MATHEMATICS',
                'description' => 'Explore numbers and logic',
                'background' => 'linear-gradient(135deg,#164E86,#4299D5)',
            ],
            'bahasa indonesia' => [
                'icon' => 'Aa',
                'label' => 'LANGUAGE & LITERATURE',
                'description' => 'Discover the power of words',
                'background' => 'linear-gradient(135deg,#9F3447,#E87986)',
            ],
            'bahasa inggris' => [
                'icon' => 'Ab',
                'label' => 'ENGLISH LANGUAGE',
                'description' => 'Connect with the world',
                'background' => 'linear-gradient(135deg,#4338A8,#818CF8)',
            ],
            'biologi' => [
                'icon' => '✿',
                'label' => 'BIOLOGY',
                'description' => 'Discover the world of life',
                'background' => 'linear-gradient(135deg,#176B50,#4DBB8B)',
            ],
            'fisika' => [
                'icon' => '⚛',
                'label' => 'PHYSICS',
                'description' => 'Understand the universe',
                'background' => 'linear-gradient(135deg,#5B3C99,#A78BFA)',
            ],
            'kimia' => [
                'icon' => '⚗',
                'label' => 'CHEMISTRY',
                'description' => 'Explore matter and reactions',
                'background' => 'linear-gradient(135deg,#A45D14,#F2B84B)',
            ],
            'sejarah' => [
                'icon' => '◷',
                'label' => 'HISTORY',
                'description' => 'Learn from the past',
                'background' => 'linear-gradient(135deg,#9A4B21,#E99A61)',
            ],
            'informatika' => [
                'icon' => '</>',
                'label' => 'INFORMATICS',
                'description' => 'Create the digital future',
                'background' => 'linear-gradient(135deg,#08677F,#36B7D2)',
            ],
            'agama' => [
                'icon' => '✧',
                'label' => 'RELIGIOUS EDUCATION',
                'description' => 'Build knowledge and character',
                'background' => 'linear-gradient(135deg,#72502D,#C49B65)',
            ],
            'ekonomi' => [
                'icon' => '↗',
                'label' => 'ECONOMICS',
                'description' => 'Understand the world of finance',
                'background' => 'linear-gradient(135deg,#126C78,#4BB7BD)',
            ],
        ];

        $defaultTheme = [
            'icon' => '▤',
            'label' => 'ACADEMIC CLASS',
            'description' => 'Explore and grow together',
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
            padding: 34px 0 65px;
            font-family: 'DM Sans', sans-serif;
        }

        .academy-page * {
            box-sizing: border-box;
        }

        .academy-wrap {
            width: min(1440px, 100%);
            margin: 0 auto;
            padding: 0 28px;
        }

        .academy-serif {
            font-family: 'Playfair Display', serif;
        }

        .academy-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 8px 15px;
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
            grid-template-columns: 1.05fr .95fr;
            align-items: center;
            gap: 30px;
            min-height: 440px;
            padding: 48px;
            border: 1px solid #E4EAF0;
            border-radius: 30px;
            background: linear-gradient(115deg, #FAFAF8 0%, #EAF2F8 100%);
            box-shadow: 0 16px 50px rgba(18,59,96,.055);
        }

        .academy-hero::before {
            content: "";
            position: absolute;
            z-index: -1;
            width: 390px;
            height: 390px;
            right: -115px;
            top: -195px;
            border: 1px solid rgba(38,123,181,.16);
            border-radius: 50%;
            box-shadow:
                0 0 0 30px rgba(38,123,181,.035),
                0 0 0 65px rgba(38,123,181,.025);
        }

        .academy-hero::after {
            content: "";
            position: absolute;
            z-index: -1;
            width: 230px;
            height: 230px;
            left: 43%;
            bottom: -165px;
            border: 1px solid rgba(38,123,181,.12);
            border-radius: 50%;
        }

        .academy-hero-content {
            position: relative;
            z-index: 2;
        }

        .academy-hero-title {
            margin-top: 25px;
            color: var(--academy-navy);
            font-family: 'Playfair Display', serif;
            font-size: clamp(39px, 5vw, 65px);
            font-weight: 700;
            letter-spacing: -2px;
            line-height: 1.08;
        }

        .academy-hero-title span {
            color: var(--academy-blue);
        }

        .academy-hero-description {
            max-width: 540px;
            margin-top: 21px;
            color: #66788A;
            font-size: 14px;
            line-height: 1.95;
        }

        .academy-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 13px 22px;
            border-radius: 100px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition: .25s ease;
        }

        .academy-button:hover {
            transform: translateY(-3px);
        }

        .academy-button-primary {
            color: #fff;
            background: var(--academy-navy);
            box-shadow: 0 8px 20px rgba(18,59,96,.18);
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
            min-height: 320px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .academy-visual-main {
            position: absolute;
            inset: 10px 15px 10px 15px;
            overflow: hidden;
            border-radius: 28px;
            background:
                linear-gradient(145deg, rgba(18,59,96,.82), rgba(38,123,181,.72)),
                linear-gradient(135deg, #123B60, #7AB6D9);
            box-shadow: 0 22px 42px rgba(18,59,96,.15);
        }

        .academy-visual-main::before {
            content: "";
            position: absolute;
            width: 300px;
            height: 300px;
            top: -120px;
            right: -70px;
            border: 1px solid rgba(255,255,255,.2);
            border-radius: 50%;
            box-shadow:
                0 0 0 25px rgba(255,255,255,.05),
                0 0 0 55px rgba(255,255,255,.035);
        }

        .academy-visual-content {
            position: relative;
            z-index: 1;
            display: flex;
            height: 100%;
            min-height: 320px;
            flex-direction: column;
            justify-content: center;
            padding: 35px;
            color: #fff;
        }

        .academy-visual-symbol {
            font-family: 'Playfair Display', serif;
            font-size: clamp(75px, 10vw, 125px);
            font-weight: 700;
            line-height: 1;
            color: rgba(255,255,255,.92);
        }

        .academy-visual-caption {
            margin-top: 15px;
            color: #D9EBF7;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .18em;
        }

        .academy-float {
            position: absolute;
            z-index: 3;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 17px;
            border: 1px solid #E8EDF0;
            border-radius: 17px;
            background: rgba(255,255,255,.96);
            box-shadow: 0 12px 35px rgba(18,59,96,.12);
        }

        .academy-float-top {
            top: 0;
            right: -5px;
        }

        .academy-float-bottom {
            bottom: 0;
            left: -8px;
        }

        .academy-float-icon {
            display: grid;
            width: 42px;
            height: 42px;
            flex-shrink: 0;
            place-items: center;
            border-radius: 13px;
            background: #E8F1F8;
            color: #267BB5;
            font-size: 20px;
        }

        .academy-float small {
            display: block;
            color: #8793A0;
            font-size: 9px;
            font-weight: 800;
            letter-spacing: .09em;
        }

        .academy-float strong {
            display: block;
            margin-top: 3px;
            color: #183B59;
            font-family: 'Playfair Display', serif;
            font-size: 14px;
        }

        /* SECTION */

        .academy-section {
            margin-top: 58px;
        }

        .academy-section-heading {
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 21px;
        }

        .academy-section-label {
            color: var(--academy-blue);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .17em;
            text-transform: uppercase;
        }

        .academy-section-title {
            margin-top: 6px;
            color: var(--academy-navy);
            font-family: 'Playfair Display', serif;
            font-size: 27px;
            font-weight: 700;
            line-height: 1.3;
        }

        .academy-section-description {
            margin-top: 6px;
            color: var(--academy-muted);
            font-size: 12px;
            line-height: 1.7;
        }

        /* STATISTICS */

        .academy-stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 17px;
        }

        .academy-stat {
            padding: 22px;
            border: 1px solid var(--academy-border);
            border-radius: 20px;
            background: #fff;
            box-shadow: 0 7px 25px rgba(18,59,96,.035);
            transition: .25s ease;
        }

        .academy-stat:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 30px rgba(18,59,96,.08);
        }

        .academy-stat-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .academy-stat-icon {
            display: grid;
            width: 43px;
            height: 43px;
            place-items: center;
            border-radius: 14px;
            background: #E8F1F8;
            color: #267BB5;
            font-size: 20px;
        }

        .academy-stat-number {
            margin-top: 19px;
            color: var(--academy-navy);
            font-family: 'Playfair Display', serif;
            font-size: 37px;
            font-weight: 700;
        }

        .academy-stat-label {
            margin-top: 3px;
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
            overflow: hidden;
            border: 1px solid var(--academy-border);
            border-radius: 22px;
            background: #fff;
            transition: .28s ease;
        }

        .academy-class-card:hover {
            transform: translateY(-5px);
            border-color: #BBD7E9;
            box-shadow: 0 17px 35px rgba(18,59,96,.09);
        }

        .academy-class-cover {
            position: relative;
            display: flex;
            min-height: 145px;
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
            color: rgba(255,255,255,.8);
            font-size: 9px;
            font-weight: 800;
            letter-spacing: .17em;
        }

        .academy-class-cover-title {
            margin-top: 9px;
            font-family: 'Playfair Display', serif;
            font-size: 21px;
            font-weight: 700;
        }

        .academy-class-symbol {
            position: relative;
            z-index: 1;
            display: grid;
            width: 66px;
            height: 66px;
            flex-shrink: 0;
            place-items: center;
            border: 1px solid rgba(255,255,255,.35);
            border-radius: 20px;
            background: rgba(255,255,255,.16);
            font-family: 'Playfair Display', serif;
            font-size: 26px;
            font-weight: 700;
            backdrop-filter: blur(8px);
        }

        .academy-class-body {
            padding: 21px;
        }

        .academy-class-name {
            color: #183B59;
            font-family: 'Playfair Display', serif;
            font-size: 20px;
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
            margin-top: 20px;
            padding: 17px 0;
            border-top: 1px solid #EEF1F3;
            border-bottom: 1px solid #EEF1F3;
        }

        .academy-class-number {
            color: #193E5D;
            font-family: 'Playfair Display', serif;
            font-size: 22px;
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
            margin-top: 16px;
        }

        .academy-class-link {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            color: #176A9E;
            font-size: 11px;
            font-weight: 800;
            text-decoration: none;
        }

        .academy-class-link:hover {
            color: #123B60;
        }

        /* PANELS */

        .academy-two-columns {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
        }

        .academy-panel {
            min-width: 0;
            padding: 25px;
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
            margin-bottom: 17px;
        }

        .academy-panel-icon {
            display: grid;
            width: 43px;
            height: 43px;
            flex-shrink: 0;
            place-items: center;
            border-radius: 14px;
            background: #E8F1F8;
            color: #267BB5;
            font-size: 19px;
        }

        .academy-list-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            padding: 15px 0;
            border-bottom: 1px solid #EDF1F4;
        }

        .academy-list-row:last-child {
            border-bottom: 0;
        }

        .academy-list-title {
            overflow: hidden;
            color: #263F55;
            font-size: 12px;
            font-weight: 700;
            text-overflow: ellipsis;
        }

        .academy-list-subtitle {
            margin-top: 5px;
            color: #8A97A3;
            font-size: 10px;
        }

        .academy-list-value {
            flex-shrink: 0;
            color: #1C6596;
            font-size: 12px;
            font-weight: 800;
            text-align: right;
        }

        .academy-empty {
            padding: 25px 15px;
            border-radius: 14px;
            background: #F6F9FB;
            color: #8493A0;
            font-size: 12px;
            text-align: center;
        }

        .academy-student-chip {
            display: inline-flex;
            align-items: center;
            padding: 9px 12px;
            border: 1px solid #E6EDF3;
            border-radius: 10px;
            background: #F8FAFC;
            color: #526579;
            font-size: 11px;
            font-weight: 600;
        }

        .academy-footer {
            margin-top: 60px;
            padding-top: 23px;
            border-top: 1px solid #E5EAEF;
            color: #9AA5AE;
            font-size: 10px;
            text-align: center;
            letter-spacing: .05em;
        }

        /* RESPONSIVE */

        @media(max-width: 1100px) {
            .academy-class-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .academy-hero {
                padding: 35px;
            }
        }

        @media(max-width: 800px) {
            .academy-hero {
                grid-template-columns: 1fr;
                gap: 25px;
            }

            .academy-visual {
                min-height: 280px;
            }

            .academy-visual-content {
                min-height: 280px;
            }

            .academy-two-columns {
                grid-template-columns: 1fr;
            }

            .academy-stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media(max-width: 560px) {
            .academy-page {
                padding: 22px 0 45px;
            }

            .academy-wrap {
                padding: 0 15px;
            }

            .academy-hero {
                padding: 27px 21px;
                border-radius: 23px;
            }

            .academy-hero-title {
                font-size: 40px;
                letter-spacing: -1.4px;
            }

            .academy-hero-description {
                font-size: 12px;
            }

            .academy-visual {
                min-height: 250px;
            }

            .academy-visual-content {
                min-height: 250px;
                padding: 24px;
            }

            .academy-float {
                padding: 10px 12px;
            }

            .academy-float-top {
                right: -3px;
            }

            .academy-float-bottom {
                left: -3px;
            }

            .academy-section {
                margin-top: 42px;
            }

            .academy-section-title {
                font-size: 23px;
            }

            .academy-class-grid {
                grid-template-columns: 1fr;
            }

            .academy-stat {
                padding: 16px;
            }

            .academy-stat-number {
                font-size: 31px;
            }

            .academy-panel {
                padding: 18px;
            }

            .academy-section-heading {
                align-items: start;
            }
        }

        @media(prefers-reduced-motion: reduce) {
            .academy-stat,
            .academy-class-card,
            .academy-button {
                transition: none;
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
                        ✦ EMPOWERING EDUCATION
                    </span>

                    <h1 class="academy-hero-title">
                        Inspiring Minds,<br>
                        <span>Building The Future.</span>
                    </h1>

                    <p class="academy-hero-description">
                        Selamat datang, {{ Auth::user()->name }}.
                        Kelola pengalaman pembelajaran, bimbing perkembangan
                        siswa, dan ciptakan lingkungan akademik yang inspiratif
                        melalui Methodist Learning Space.
                    </p>

                    <div class="mt-7 flex flex-wrap gap-3">

                        <a href="{{ route('guru.classes.index') }}"
                           class="academy-button academy-button-primary">
                            Explore My Classes
                            <span>→</span>
                        </a>

                        <a href="{{ route('guru.exams.index') }}"
                           class="academy-button academy-button-secondary">
                            Manage Examinations
                        </a>

                    </div>

                    <div class="mt-8 flex flex-wrap gap-7">

                        <div>
                            <p class="academy-serif text-3xl font-bold text-[#123B60]">
                                {{ $totalClasses }}
                            </p>
                            <p class="mt-1 text-xs text-slate-500">
                                Classes
                            </p>
                        </div>

                        <div class="h-12 w-px bg-slate-200"></div>

                        <div>
                            <p class="academy-serif text-3xl font-bold text-[#123B60]">
                                {{ $totalStudents }}
                            </p>
                            <p class="mt-1 text-xs text-slate-500">
                                Students
                            </p>
                        </div>

                        <div class="h-12 w-px bg-slate-200"></div>

                        <div>
                            <p class="academy-serif text-3xl font-bold text-[#123B60]">
                                {{ $totalAssignments + $totalExams }}
                            </p>
                            <p class="mt-1 text-xs text-slate-500">
                                Learning Activities
                            </p>
                        </div>

                    </div>
                </div>

                {{-- HERO VISUAL --}}

                <div class="academy-visual">

                    <div class="academy-visual-main">
                        <div class="academy-visual-content">

                            <p class="text-xs font-bold tracking-[.2em] text-sky-100">
                                METHODIST LEARNING SPACE
                            </p>

                            <div class="academy-visual-symbol">
                                M.
                            </div>

                            <p class="academy-serif mt-3 text-2xl font-semibold">
                                Learn. Grow. Achieve.
                            </p>

                            <p class="mt-2 max-w-xs text-xs leading-6 text-sky-100">
                                Creating meaningful learning experiences
                                for a brighter future.
                            </p>

                        </div>
                    </div>

                    <div class="academy-float academy-float-top">
                        <div class="academy-float-icon">
                            ♧
                        </div>
                        <div>
                            <small>ACADEMIC EXCELLENCE</small>
                            <strong>Quality Education</strong>
                        </div>
                    </div>

                    <div class="academy-float academy-float-bottom">
                        <div class="academy-float-icon">
                            ♙
                        </div>
                        <div>
                            <small>OUR COMMUNITY</small>
                            <strong>Growing Together</strong>
                        </div>
                    </div>

                </div>

            </section>

            {{-- ==========================================
                 STATISTICS
            =========================================== --}}

            <section class="academy-section">

                <div class="academy-section-heading">
                    <div>
                        <p class="academy-section-label">At a glance</p>
                        <h2 class="academy-section-title">
                            Your Teaching Overview
                        </h2>
                        <p class="academy-section-description">
                            Ringkasan aktivitas pembelajaran Anda.
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

                        <p class="academy-stat-number">
                            {{ $totalClasses }}
                        </p>

                        <p class="academy-stat-label">
                            Classes You Teach
                        </p>
                    </article>

                    <article class="academy-stat">
                        <div class="academy-stat-top">
                            <div class="academy-stat-icon">♙</div>
                            <span class="text-[10px] font-bold tracking-wider text-blue-600">
                                STUDENTS
                            </span>
                        </div>

                        <p class="academy-stat-number">
                            {{ $totalStudents }}
                        </p>

                        <p class="academy-stat-label">
                            Total Enrolled Students
                        </p>
                    </article>

                    <article class="academy-stat">
                        <div class="academy-stat-top">
                            <div class="academy-stat-icon">✎</div>
                            <span class="text-[10px] font-bold tracking-wider text-blue-600">
                                ASSIGNMENTS
                            </span>
                        </div>

                        <p class="academy-stat-number">
                            {{ $totalAssignments }}
                        </p>

                        <p class="academy-stat-label">
                            Created Assignments
                        </p>
                    </article>

                    <article class="academy-stat">
                        <div class="academy-stat-top">
                            <div class="academy-stat-icon">✓</div>
                            <span class="text-[10px] font-bold tracking-wider text-blue-600">
                                E-EXAM
                            </span>
                        </div>

                        <p class="academy-stat-number">
                            {{ $totalExams }}
                        </p>

                        <p class="academy-stat-label">
                            Created Examinations
                        </p>
                    </article>

                </div>

            </section>

            {{-- ==========================================
                 MY CLASSES
            =========================================== --}}

            <section class="academy-section">

                <div class="academy-section-heading">
                    <div>
                        <p class="academy-section-label">Teaching space</p>
                        <h2 class="academy-section-title">
                            My Classes
                        </h2>
                        <p class="academy-section-description">
                            Ruang pembelajaran yang Anda kelola.
                        </p>
                    </div>

                    <a href="{{ route('guru.classes.index') }}"
                       class="text-xs font-bold text-blue-700 hover:text-blue-900">
                        View All Classes →
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

                        <article class="academy-class-card">

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

                                <div class="academy-class-symbol">
                                    {{ $theme['icon'] }}
                                </div>

                            </div>

                            <div class="academy-class-body">

                                <p class="text-[9px] font-bold uppercase tracking-[.15em] text-slate-400">
                                    SCHOOL CLASS
                                </p>

                                <h3 class="academy-class-name">
                                    {{ $class->class_name }}
                                </h3>

                                <p class="academy-class-meta">
                                    {{ $class->student_count }} enrolled students
                                </p>

                                <div class="academy-class-numbers">

                                    <div>
                                        <p class="academy-class-number">
                                            {{ $class->student_count }}
                                        </p>
                                        <p class="academy-class-number-label">
                                            Students
                                        </p>
                                    </div>

                                    <div>
                                        <p class="academy-class-number">
                                            {{ $class->assignment_count }}
                                        </p>
                                        <p class="academy-class-number-label">
                                            Assignments
                                        </p>
                                    </div>

                                    <div>
                                        <p class="academy-class-number">
                                            {{ $class->exam_count }}
                                        </p>
                                        <p class="academy-class-number-label">
                                            Examinations
                                        </p>
                                    </div>

                                </div>

                                <div class="academy-class-footer">

                                    <span class="text-[10px] text-slate-400">
                                        {{ $class->published_exam_count }} published exams
                                    </span>

                                    <a href="{{ route('guru.classes.show', $class->cst_id) }}"
                                       class="academy-class-link">
                                        Open Class <span>→</span>
                                    </a>

                                </div>

                            </div>

                        </article>

                    @empty

                        <div class="col-span-full rounded-2xl border border-dashed border-blue-200 bg-white px-6 py-14 text-center">

                            <div class="mx-auto grid h-16 w-16 place-items-center rounded-full bg-blue-50 text-3xl text-blue-600">
                                ▤
                            </div>

                            <h3 class="academy-serif mt-5 text-2xl font-bold text-[#123B60]">
                                Your Teaching Journey Starts Here
                            </h3>

                            <p class="mx-auto mt-3 max-w-md text-sm leading-7 text-slate-500">
                                Belum ada kelas yang tersedia untuk ditampilkan.
                                Kelas yang Anda ampu akan muncul di bagian ini.
                            </p>

                            <a href="{{ route('guru.classes.index') }}"
                               class="academy-button academy-button-primary mt-5">
                                Manage My Classes →
                            </a>

                        </div>

                    @endforelse

                </div>

            </section>

            {{-- ==========================================
                 ASSIGNMENTS AND SCHEDULE
            =========================================== --}}

            <section class="academy-section academy-two-columns">

                {{-- ASSIGNMENT STATUS --}}

                <div class="academy-panel">

                    <div class="academy-panel-header">
                        <div>
                            <p class="academy-section-label">
                                Academic Assessment
                            </p>

                            <h3 class="academy-section-title text-xl">
                                Assignment Overview
                            </h3>
                        </div>

                        <div class="academy-panel-icon">
                            ✎
                        </div>
                    </div>

                    @forelse($assignmentStatuses as $assignment)

                        <div class="academy-list-row">

                            <div class="min-w-0">
                                <p class="academy-list-title">
                                    {{ $assignment->title }}
                                </p>

                                <p class="academy-list-subtitle">
                                    {{ $assignment->class_name }}
                                </p>
                            </div>

                            <div class="academy-list-value">
                                {{ $assignment->graded_count }}/{{ $assignment->submitted_count }}

                                <p class="mt-1 text-[9px] font-medium text-slate-400">
                                    Graded
                                </p>
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
                                    {{ $totalUngraded }} essays awaiting review
                                </p>

                                <p class="mt-1 text-[10px] text-amber-700">
                                    Terdapat pekerjaan siswa yang menunggu penilaian.
                                </p>
                            </div>

                        </div>

                    @endif

                </div>

                {{-- TEACHING SCHEDULE --}}

                <div class="academy-panel">

                    <div class="academy-panel-header">

                        <div>
                            <p class="academy-section-label">
                                Weekly Activities
                            </p>

                            <h3 class="academy-section-title text-xl">
                                Teaching Schedule
                            </h3>
                        </div>

                        <div class="academy-panel-icon">
                            ◷
                        </div>

                    </div>

                    @forelse($schedule as $slot)

                        <div class="academy-list-row">

                            <div class="min-w-0">
                                <p class="academy-list-title">
                                    {{ ucfirst($slot->day) }}
                                </p>

                                <p class="academy-list-subtitle">
                                    {{ $slot->class_name }} · {{ $slot->subject_name }}
                                </p>
                            </div>

                            <span class="shrink-0 rounded-full bg-blue-50 px-3 py-2 text-[10px] font-bold text-blue-800">
                                {{ substr($slot->start_time, 0, 5) }}
                                –
                                {{ substr($slot->end_time, 0, 5) }}
                            </span>

                        </div>

                    @empty

                        <div class="academy-empty">
                            Belum ada jadwal mengajar.
                        </div>

                    @endforelse

                </div>

            </section>

            {{-- ==========================================
                 STUDENTS AND GRADES
            =========================================== --}}

            <section class="academy-section academy-two-columns">

                {{-- STUDENT DIRECTORY --}}

                <div class="academy-panel">

                    <div class="academy-panel-header">

                        <div>
                            <p class="academy-section-label">
                                Learning Community
                            </p>

                            <h3 class="academy-section-title text-xl">
                                Student Directory
                            </h3>
                        </div>

                        <div class="academy-panel-icon">
                            ♙
                        </div>

                    </div>

                    @forelse($roster as $cstId => $students)

                        <div class="mb-5 last:mb-0">

                            <p class="mb-3 text-[10px] font-extrabold uppercase tracking-wider text-slate-500">
                                {{ $students->first()->class_name }}
                                ·
                                {{ $students->first()->subject_name }}
                            </p>

                            <div class="flex flex-wrap gap-2">

                                @foreach($students as $student)

                                    <span class="academy-student-chip">
                                        {{ $student->student_name }}
                                    </span>

                                @endforeach

                            </div>

                        </div>

                    @empty

                        <div class="academy-empty">
                            Belum ada siswa yang terdaftar.
                        </div>

                    @endforelse

                </div>

                {{-- LATEST GRADES --}}

                <div class="academy-panel">

                    <div class="academy-panel-header">

                        <div>
                            <p class="academy-section-label">
                                Student Achievement
                            </p>

                            <h3 class="academy-section-title text-xl">
                                Latest Grades
                            </h3>
                        </div>

                        <div class="academy-panel-icon">
                            ★
                        </div>

                    </div>

                    @forelse($gradebook->take(10) as $grade)

                        <div class="academy-list-row">

                            <div class="min-w-0">

                                <p class="academy-list-title">
                                    {{ $grade->student_name }}
                                </p>

                                <p class="academy-list-subtitle">
                                    {{ $grade->source_title }}
                                </p>

                            </div>

                            <div class="academy-list-value">

                                {{ $grade->score }}

                                <p class="mt-1 text-[9px] font-medium text-slate-400">
                                    {{ $grade->predicate }}
                                </p>

                            </div>

                        </div>

                    @empty

                        <div class="academy-empty">
                            Belum ada nilai yang tersedia.
                        </div>

                    @endforelse

                </div>

            </section>

            {{-- ==========================================
                 FOOTER
            =========================================== --}}

            <footer class="academy-footer">
                <p class="academy-serif text-base font-bold text-[#123B60]">
                    Methodist Learning Space
                </p>

                <p class="mt-2">
                    EMPOWERING MINDS · INSPIRING EXCELLENCE · BUILDING THE FUTURE
                </p>

                <p class="mt-3">
                    © {{ date('Y') }} Methodist School. All rights reserved.
                </p>
            </footer>

        </div>
    </main>

</x-app-layout>
