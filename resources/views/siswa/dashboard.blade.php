<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold tracking-tight text-slate-800">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --blue-primary: #2563EB;
            --blue-dark: #153E82;
            --blue-deep: #102C59;
            --blue-soft: #EFF6FF;
            --blue-light: #DBEAFE;
            --navy: #14213D;
            --muted: #64748B;
            --border: #E7EDF5;
            --background: #F5F8FD;
        }

        .student-dashboard {
            min-height: 100vh;
            padding: 48px 0 72px;
            background: var(--background);
            font-family: 'DM Sans', sans-serif;
            overflow: hidden;
        }

        .student-wrap {
            max-width: 1160px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* TOP INFORMATION */

        .welcome-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
            margin-bottom: 26px;
        }

        .welcome-breadcrumb {
            color: #94A3B8;
            font-size: 12px;
            font-weight: 600;
        }

        .welcome-breadcrumb span {
            color: #2563EB;
        }

        .welcome-date {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 14px;
            border: 1px solid var(--border);
            border-radius: 999px;
            background: white;
            color: #64748B;
            font-size: 11px;
            font-weight: 700;
        }

        /* HERO */

        .student-hero {
            position: relative;
            display: grid;
            grid-template-columns: 1.15fr .85fr;
            align-items: center;
            gap: 35px;
            min-height: 390px;
            padding: 48px;
            overflow: hidden;
            border-radius: 30px;
            background:
                radial-gradient(circle at 85% 15%, rgba(96,165,250,.28), transparent 30%),
                linear-gradient(125deg, #102C59 0%, #174B98 55%, #2563EB 100%);
            box-shadow: 0 22px 55px rgba(21, 62, 130, .16);
        }

        .student-hero::before {
            content: '';
            position: absolute;
            width: 350px;
            height: 350px;
            top: -190px;
            right: 15%;
            border: 1px solid rgba(255,255,255,.12);
            border-radius: 50%;
        }

        .student-hero::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            bottom: -220px;
            left: 35%;
            background: rgba(255,255,255,.06);
            border-radius: 50%;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 9px 15px;
            margin-bottom: 25px;
            border: 1px solid rgba(255,255,255,.17);
            border-radius: 999px;
            background: rgba(255,255,255,.10);
            color: #DBEAFE;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .hero-badge-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #60A5FA;
            box-shadow: 0 0 12px #60A5FA;
        }

        .hero-title {
            margin: 0;
            color: white;
            font-family: 'Playfair Display', serif;
            font-size: clamp(34px, 4.5vw, 52px);
            font-weight: 700;
            line-height: 1.16;
            letter-spacing: -.8px;
        }

        .hero-title em {
            color: #93C5FD;
            font-style: normal;
            overflow-wrap: anywhere;
        }

        .hero-description {
            max-width: 490px;
            margin: 20px 0 27px;
            color: #DCEBFF;
            font-size: 13px;
            line-height: 1.9;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .hero-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 13px 21px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 800;
            text-decoration: none;
            transition: .25s ease;
        }

        .hero-button-primary {
            background: white;
            color: #174B98;
            box-shadow: 0 8px 20px rgba(0,0,0,.12);
        }

        .hero-button-primary:hover {
            background: #DBEAFE;
            color: #153E82;
            transform: translateY(-3px);
        }

        .hero-button-secondary {
            border: 1px solid rgba(255,255,255,.25);
            background: rgba(255,255,255,.08);
            color: white;
        }

        .hero-button-secondary:hover {
            background: rgba(255,255,255,.17);
            color: white;
            transform: translateY(-3px);
        }

        /* HERO VISUAL */

        .hero-visual {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 270px;
        }

        .visual-main {
            position: relative;
            width: 250px;
            height: 250px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255,255,255,.23);
            border-radius: 34px;
            background: linear-gradient(145deg, rgba(255,255,255,.18), rgba(255,255,255,.06));
            box-shadow: 0 25px 50px rgba(0,0,0,.13);
            backdrop-filter: blur(12px);
            transform: rotate(-5deg);
        }

        .visual-main-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 90px;
            height: 90px;
            margin-bottom: 20px;
            border-radius: 27px;
            background: white;
            color: #2563EB;
            box-shadow: 0 14px 28px rgba(0,0,0,.15);
        }

        .visual-main-icon svg {
            width: 48px;
            height: 48px;
        }

        .visual-main strong {
            color: white;
            font-family: 'Playfair Display', serif;
            font-size: 21px;
            letter-spacing: .2px;
        }

        .visual-main small {
            margin-top: 6px;
            color: #DBEAFE;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .visual-decoration {
            position: absolute;
            width: 58px;
            height: 58px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255,255,255,.2);
            border-radius: 18px;
            background: rgba(255,255,255,.14);
            color: white;
            backdrop-filter: blur(10px);
        }

        .visual-decoration svg {
            width: 26px;
            height: 26px;
        }

        .visual-decoration.one {
            top: 7px;
            right: 18px;
            transform: rotate(12deg);
        }

        .visual-decoration.two {
            bottom: 3px;
            left: 5px;
            transform: rotate(-12deg);
        }

        /* SECTION HEADER */

        .section-heading {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin: 48px 0 20px;
        }

        .section-eyebrow {
            margin-bottom: 7px;
            color: #2563EB;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.8px;
            text-transform: uppercase;
        }

        .section-title {
            margin: 0;
            color: var(--navy);
            font-family: 'Playfair Display', serif;
            font-size: 25px;
            font-weight: 700;
        }

        .section-description {
            margin-top: 7px;
            color: #8492A6;
            font-size: 12px;
        }

        /* QUICK ACCESS */

        .quick-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
        }

        .quick-card {
            position: relative;
            display: flex;
            flex-direction: column;
            min-height: 225px;
            padding: 27px;
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 22px;
            background: white;
            box-shadow: 0 8px 28px rgba(15, 40, 80, .035);
            text-decoration: none;
            transition: transform .25s, border-color .25s, box-shadow .25s;
        }

        .quick-card:hover {
            transform: translateY(-5px);
            border-color: #BFDBFE;
            box-shadow: 0 18px 38px rgba(37,99,235,.09);
        }

        .quick-card::after {
            content: '';
            position: absolute;
            width: 155px;
            height: 155px;
            right: -70px;
            bottom: -75px;
            border-radius: 50%;
            background: #EFF6FF;
            transition: transform .3s;
        }

        .quick-card:hover::after {
            transform: scale(1.3);
        }

        .quick-card-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
        }

        .quick-icon {
            width: 54px;
            height: 54px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 17px;
            background: #EFF6FF;
            color: #2563EB;
        }

        .quick-icon svg {
            width: 27px;
            height: 27px;
        }

        .quick-arrow {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #F1F5F9;
            color: #64748B;
            transition: .25s;
        }

        .quick-card:hover .quick-arrow {
            background: #2563EB;
            color: white;
            transform: translateX(3px);
        }

        .quick-card h3 {
            position: relative;
            z-index: 1;
            margin: 23px 0 8px;
            color: var(--navy);
            font-family: 'Playfair Display', serif;
            font-size: 23px;
            font-weight: 700;
        }

        .quick-card p {
            position: relative;
            z-index: 1;
            max-width: 360px;
            margin: 0;
            color: #718096;
            font-size: 12px;
            line-height: 1.8;
        }

        .quick-link {
            position: relative;
            z-index: 1;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            margin-top: 20px;
            color: #2563EB;
            font-size: 11px;
            font-weight: 800;
        }

        /* BOTTOM INFORMATION */

        .bottom-grid {
            display: grid;
            grid-template-columns: 1.2fr .8fr;
            gap: 20px;
            margin-top: 25px;
        }

        .info-card {
            position: relative;
            overflow: hidden;
            padding: 25px;
            border: 1px solid var(--border);
            border-radius: 22px;
            background: white;
            box-shadow: 0 8px 28px rgba(15,40,80,.035);
        }

        .info-heading {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 19px;
        }

        .info-heading-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            flex-shrink: 0;
            border-radius: 13px;
            background: #EFF6FF;
            color: #2563EB;
        }

        .info-heading-icon svg {
            width: 21px;
            height: 21px;
        }

        .info-heading h3 {
            margin: 0;
            color: var(--navy);
            font-size: 13px;
            font-weight: 800;
        }

        .info-heading p {
            margin-top: 4px;
            color: #94A3B8;
            font-size: 10px;
        }

        .info-text {
            color: #64748B;
            font-size: 12px;
            line-height: 1.9;
        }

        .quote-card {
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-height: 190px;
            border: 0;
            background: linear-gradient(135deg, #EAF2FF, #F8FAFF);
        }

        .quote-mark {
            color: #93C5FD;
            font-family: 'Playfair Display', serif;
            font-size: 58px;
            line-height: .8;
        }

        .quote-text {
            margin: 12px 0;
            color: #1E3A8A;
            font-family: 'Playfair Display', serif;
            font-size: 17px;
            font-weight: 700;
            line-height: 1.6;
        }

        .quote-author {
            color: #64748B;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .5px;
        }

        /* FOOTER */

        .dashboard-footer {
            margin-top: 40px;
            padding-top: 22px;
            border-top: 1px solid #E2E8F0;
            text-align: center;
        }

        .dashboard-footer p {
            color: #94A3B8;
            font-size: 10px;
            letter-spacing: .3px;
        }

        /* RESPONSIVE */

        @media (max-width: 900px) {
            .student-hero {
                grid-template-columns: 1fr;
                gap: 10px;
                padding: 38px;
            }

            .hero-visual {
                min-height: 280px;
            }

            .bottom-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .student-dashboard {
                padding: 28px 0 50px;
            }

            .student-wrap {
                padding: 0 16px;
            }

            .student-hero {
                min-height: auto;
                padding: 28px 23px;
                border-radius: 23px;
            }

            .hero-title {
                font-size: 36px;
            }

            .hero-description {
                font-size: 12px;
            }

            .hero-actions {
                flex-direction: column;
            }

            .hero-button {
                width: 100%;
            }

            .hero-visual {
                min-height: 245px;
            }

            .visual-main {
                width: 205px;
                height: 205px;
            }

            .visual-main-icon {
                width: 72px;
                height: 72px;
            }

            .visual-main-icon svg {
                width: 39px;
                height: 39px;
            }

            .quick-grid {
                grid-template-columns: 1fr;
                gap: 14px;
            }

            .quick-card {
                min-height: 205px;
                padding: 23px;
            }

            .section-heading {
                margin-top: 36px;
            }

            .section-title {
                font-size: 22px;
            }

            .info-card {
                padding: 22px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .student-dashboard *,
            .student-dashboard *::before,
            .student-dashboard *::after {
                transition: none !important;
            }
        }

        /* ===== BLUE DASHBOARD REDESIGN ===== */
        :root {
            --blue-primary: #1761C5;
            --blue-dark: #10458F;
            --blue-deep: #0B2F67;
            --blue-soft: #EEF5FF;
            --blue-light: #DCEAFF;
            --navy: #102B55;
            --muted: #64748B;
            --border: #DCE7F7;
            --background: #F7FAFF;
        }

        html { scroll-behavior: smooth; }

        .dashboard-sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 50;
            width: 88px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 24px;
            padding: 25px 12px;
            background: linear-gradient(180deg, #10458F 0%, #092D65 100%);
            box-shadow: 5px 0 24px rgba(12, 48, 100, .12);
        }
        .sidebar-brand {
            width: 48px; height: 48px;
            display: grid; place-items: center;
            margin-bottom: 22px;
            border: 1px solid rgba(255,255,255,.45);
            border-radius: 14px;
            color: white;
            font-family: 'Playfair Display', serif;
            font-size: 25px; font-weight: 800;
            background: rgba(255,255,255,.12);
        }
        .sidebar-links {
            display: flex; flex-direction: column;
            align-items: center; gap: 13px; width: 100%;
        }
        .sidebar-link {
            width: 54px; height: 54px;
            display: grid; place-items: center;
            border-radius: 15px;
            color: #EAF2FF;
            text-decoration: none;
            transition: .2s ease;
        }
        .sidebar-link:hover, .sidebar-link.active {
            color: #10458F; background: #fff;
            box-shadow: 0 5px 15px rgba(0,0,0,.12);
        }
        .sidebar-link svg { width: 24px; height: 24px; }
        .student-dashboard {
            min-height: 100vh;
            padding: 32px 0 45px 88px;
            background: #F7FAFF;
        }
        .student-wrap { max-width: 1320px; padding: 0 34px; }
        .welcome-top { margin-bottom: 22px; }
        .welcome-breadcrumb {
            color: #102B55; font-size: 24px;
            font-family: 'Playfair Display', serif;
            font-weight: 700; letter-spacing: -.3px;
        }
        .welcome-breadcrumb span { display: none; }
        .welcome-date {
            padding: 10px 16px; color: #405575;
            border-color: #DCE7F7; background: #fff;
            font-size: 12px;
        }
        .student-hero {
            min-height: 300px;
            grid-template-columns: 1.05fr .95fr;
            gap: 20px; padding: 38px 44px;
            border: 1px solid #D8E5F8; border-radius: 18px;
            background: linear-gradient(115deg, #F0F6FF 0%, #FFFFFF 100%);
            box-shadow: none;
        }
        .student-hero::before {
            width: 260px; height: 260px; top: -160px; right: 12%;
            border-color: rgba(23,97,197,.12);
        }
        .student-hero::after {
            width: 220px; height: 220px; bottom: -170px; left: 35%;
            background: rgba(23,97,197,.045);
        }
        .hero-badge {
            padding: 0; margin-bottom: 16px;
            border: 0; background: transparent;
            color: #1761C5; font-size: 11px; letter-spacing: 1px;
        }
        .hero-badge-dot { background: #1761C5; box-shadow: none; }
        .hero-title {
            color: #102B55; font-size: clamp(30px, 3.4vw, 40px);
        }
        .hero-title em { color: #1761C5; }
        .hero-description { color: #536783; margin: 15px 0 20px; }
        .hero-button { border-radius: 9px; }
        .hero-button-primary {
            color: #fff; background: #1761C5;
            box-shadow: 0 5px 14px rgba(23,97,197,.18);
        }
        .hero-button-primary:hover { color: #fff; background: #10458F; }
        .hero-button-secondary {
            color: #1761C5; border-color: #B9D2F5;
            background: #fff;
        }
        .hero-button-secondary:hover { color: #10458F; background: #EAF2FF; }
        .hero-visual { min-height: 220px; }
        .visual-main {
            width: 225px; height: 205px;
            border: 1px solid #C9DCF8; border-radius: 25px;
            background: linear-gradient(145deg, #DCEAFF, #F8FBFF);
            box-shadow: 0 14px 35px rgba(22,78,150,.10);
            transform: rotate(-3deg);
        }
        .visual-main-icon {
            width: 76px; height: 76px; border-radius: 22px;
            color: #1761C5; background: white;
            box-shadow: 0 8px 20px rgba(23,97,197,.12);
        }
        .visual-main strong { color: #102B55; }
        .visual-main small { color: #54739D; }
        .visual-decoration {
            color: #1761C5; background: #fff;
            border-color: #C9DCF8; box-shadow: 0 7px 18px rgba(23,97,197,.08);
        }
        .section-heading { margin: 34px 0 16px; }
        .section-eyebrow { color: #1761C5; }
        .section-title { font-size: 24px; }
        .quick-grid { gap: 20px; }
        .quick-card {
            min-height: 215px; padding: 25px 25px 25px 158px;
            justify-content: center;
            border: 1px solid #DCE7F7; border-radius: 17px;
            box-shadow: 0 5px 18px rgba(16,43,85,.045);
        }
        .quick-card::before {
            content: ''; position: absolute;
            inset: 0 auto 0 0; width: 126px;
            background: linear-gradient(160deg, #1767CE, #0D438E);
            border-radius: 16px 0 0 16px;
        }
        .quick-card::after {
            width: 115px; height: 115px; right: -48px; bottom: -55px;
            background: #EEF5FF;
        }
        .quick-card:hover { border-color: #AFCBF1; box-shadow: 0 12px 25px rgba(23,97,197,.10); }
        .quick-card-top { position: static; }
        .quick-icon {
            position: absolute; z-index: 2;
            left: 25px; top: 50%; transform: translateY(-50%);
            width: 76px; height: 76px; border-radius: 50%;
            color: #1761C5; background: #fff;
            box-shadow: 0 7px 18px rgba(0,0,0,.10);
        }
        .quick-icon svg { width: 38px; height: 38px; }
        .quick-arrow {
            position: absolute; top: 18px; right: 18px;
            background: #EEF5FF; color: #1761C5;
        }
        .quick-card:hover .quick-arrow { background: #1761C5; }
        .quick-card h3 { margin: 0 0 9px; font-size: 25px; color: #102B55; }
        .quick-card p { max-width: 340px; color: #5B6D86; }
        .quick-link { margin-top: 15px; color: #1761C5; }
        .bottom-grid { grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 25px; }
        .info-card {
            padding: 24px; border: 1px solid #DCE7F7;
            border-radius: 17px; box-shadow: 0 5px 18px rgba(16,43,85,.035);
        }
        .info-heading { padding-bottom: 14px; border-bottom: 1px solid #E7EEF8; }
        .info-heading-icon { color: #1761C5; background: #EAF2FF; border-radius: 50%; }
        .info-heading h3 { color: #102B55; font-size: 15px; }
        .info-text { color: #586B85; }
        .quote-card {
            min-height: 190px; border: 1px solid #DCE7F7;
            background: linear-gradient(135deg, #EAF2FF, #F8FBFF);
        }
        .quote-mark { color: #8BB7F0; }
        .quote-text { color: #174783; }
        .dashboard-footer { border-color: #DCE7F7; }
        .dashboard-footer p { color: #7C8DA6; }

        @media (max-width: 900px) {
            .student-dashboard { padding-left: 88px; }
            .student-wrap { padding: 0 22px; }
            .student-hero { grid-template-columns: 1fr; }
            .hero-visual { min-height: 220px; }
            .bottom-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 640px) {
            .dashboard-sidebar { width: 62px; padding: 18px 6px; gap: 15px; }
            .sidebar-brand { width: 40px; height: 40px; font-size: 21px; }
            .sidebar-link { width: 46px; height: 46px; }
            .student-dashboard { padding: 24px 0 40px 62px; }
            .student-wrap { padding: 0 13px; }
            .welcome-breadcrumb { font-size: 20px; }
            .student-hero { padding: 25px 20px; }
            .hero-title { font-size: 30px; }
            .hero-actions { flex-direction: column; }
            .hero-button { width: 100%; }
            .quick-grid { grid-template-columns: 1fr; }
            .quick-card { min-height: 205px; padding: 24px 18px 24px 112px; }
            .quick-card::before { width: 88px; }
            .quick-icon { left: 15px; width: 58px; height: 58px; }
            .quick-icon svg { width: 30px; height: 30px; }
            .quick-card h3 { font-size: 21px; }
            .quick-card p { font-size: 11px; }
            .info-card { padding: 19px; }
        }

    </style>


    <aside class="dashboard-sidebar" aria-label="Navigasi utama">
        <a href="#dashboard-home" class="sidebar-brand" aria-label="Methodist Learning Space">M</a>
        <nav class="sidebar-links">
            <a href="#dashboard-home" class="sidebar-link active" aria-label="Dashboard" title="Dashboard">
                <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m3 10 9-7 9 7M5 9v11h14V9M9 20v-6h6v6"/></svg>
            </a>
            <a href="{{ route('siswa.classes.index') }}" class="sidebar-link" aria-label="E-Class" title="E-Class">
                <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.25v13m0-13C10.8 5.5 9.2 5 7.5 5S4.2 5.5 3 6.25v13C4.2 18.5 5.8 18 7.5 18s3.3.5 4.5 1.25m0-13C13.2 5.5 14.8 5 16.5 5s3.3.5 4.5 1.25v13c-1.2-.75-2.8-1.25-4.5-1.25s-3.3.5-4.5 1.25"/></svg>
            </a>
            <a href="{{ route('siswa.exams.index') }}" class="sidebar-link" aria-label="E-Exam" title="E-Exam">
                <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="5" y="4" width="14" height="17" rx="2"/><path stroke-linecap="round" d="M9 4V2h6v2M8 10h8M8 14h8M8 18h5"/></svg>
            </a>
            <a href="#informasi-dashboard" class="sidebar-link" aria-label="Informasi" title="Informasi">
                <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 5h16v12H8l-4 4V5zM8 9h8M8 13h5"/></svg>
            </a>
        </nav>
    </aside>

    <div class="student-dashboard" id="dashboard-home">
        <div class="student-wrap">

            {{-- TOP BAR --}}
            <div class="welcome-top">
                <div class="welcome-breadcrumb">
                    METHODIST LEARNING SPACE
                    <span> / STUDENT PORTAL</span>
                </div>

                <div class="welcome-date">
                    <svg width="15" height="15" fill="none" stroke="currentColor"
                         stroke-width="1.8" viewBox="0 0 24 24">
                        <rect x="3" y="5" width="18" height="16" rx="2"/>
                        <path stroke-linecap="round" d="M16 3v4M8 3v4M3 10h18"/>
                    </svg>
                    {{ now()->locale('id')->translatedFormat('l, d F Y') }}
                </div>
            </div>

            {{-- HERO SECTION --}}
            <section class="student-hero">

                <div class="hero-content">
                    <div class="hero-badge">
                        <span class="hero-badge-dot"></span>
                        {{ __('STUDENT LEARNING PORTAL') }}
                    </div>

                    <h1 class="hero-title">
                        {{ __('Halo,') }}
                        <em>{{ Auth::user()->name }}</em>.
                        <br>
                        {{ __('Siap belajar hari ini?') }}
                    </h1>

                    <p class="hero-description">
                        {{ __('Selamat datang di ruang belajar digital Methodist. Akses kelas, materi pembelajaran, tugas, ujian, dan kuis melalui satu platform yang terintegrasi.') }}
                    </p>

                    <div class="hero-actions">
                        <a href="{{ route('siswa.classes.index') }}"
                           class="hero-button hero-button-primary">
                            {{ __('Jelajahi E-Class') }}
                            <span aria-hidden="true">→</span>
                        </a>

                        <a href="{{ route('siswa.exams.index') }}"
                           class="hero-button hero-button-secondary">
                            {{ __('Lihat E-Exam') }}
                            <span aria-hidden="true">↗</span>
                        </a>
                    </div>
                </div>

                {{-- HERO VISUAL --}}
                <div class="hero-visual" aria-hidden="true">

                    <div class="visual-main">
                        <div class="visual-main-icon">
                            <svg fill="none" stroke="currentColor"
                                 stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M12 6.25v13m0-13C10.83 5.48 9.25 5 7.5 5S4.17 5.48 3 6.25v13C4.17 18.48 5.75 18 7.5 18s3.33.48 4.5 1.25m0-13C13.17 5.48 14.75 5 16.5 5c1.75 0 3.33.48 4.5 1.25v13C19.83 18.48 18.25 18 16.5 18c-1.75 0-3.33.48-4.5 1.25"/>
                            </svg>
                        </div>

                        <strong>Learn & Grow</strong>
                        <small>Knowledge starts here</small>
                    </div>

                    <div class="visual-decoration one">
                        <svg fill="none" stroke="currentColor"
                             stroke-width="1.6" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>

                    <div class="visual-decoration two">
                        <svg fill="none" stroke="currentColor"
                             stroke-width="1.6" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 3l1.9 5.8L20 11l-6.1 2.2L12 19l-1.9-5.8L4 11l6.1-2.2L12 3z"/>
                            <path stroke-linecap="round" d="M19 16v5m-2.5-2.5h5"/>
                        </svg>
                    </div>

                </div>
            </section>

            {{-- QUICK ACCESS HEADING --}}
            <div class="section-heading">
                <div>
                    <div class="section-eyebrow">YOUR LEARNING SPACE</div>
                    <h2 class="section-title">{{ __('Akses Pembelajaran') }}</h2>
                    <p class="section-description">
                        {{ __('Semua kebutuhan akademikmu tersedia di sini.') }}
                    </p>
                </div>
            </div>

            {{-- QUICK ACCESS CARDS --}}
            <div class="quick-grid">

                {{-- E-CLASS --}}
                <a href="{{ route('siswa.classes.index') }}"
                   class="quick-card">

                    <div class="quick-card-top">
                        <div class="quick-icon">
                            <svg fill="none" stroke="currentColor"
                                 stroke-width="1.7" viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M12 6.25v13m0-13C10.83 5.48 9.25 5 7.5 5S4.17 5.48 3 6.25v13C4.17 18.48 5.75 18 7.5 18s3.33.48 4.5 1.25m0-13C13.17 5.48 14.75 5 16.5 5c1.75 0 3.33.48 4.5 1.25v13C19.83 18.48 18.25 18 16.5 18c-1.75 0-3.33.48-4.5 1.25"/>
                            </svg>
                        </div>

                        <span class="quick-arrow">↗</span>
                    </div>

                    <h3>{{ __('E-Class') }}</h3>

                    <p>
                        {{ __('Temukan kelas yang kamu ikuti, pelajari materi, unduh bahan ajar, dan kumpulkan tugas dari guru.') }}
                    </p>

                    <span class="quick-link">
                        {{ __('Masuk ke E-Class') }}
                        <span>→</span>
                    </span>
                </a>

                {{-- E-EXAM --}}
                <a href="{{ route('siswa.exams.index') }}"
                   class="quick-card">

                    <div class="quick-card-top">
                        <div class="quick-icon">
                            <svg fill="none" stroke="currentColor"
                                 stroke-width="1.7" viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>

                        <span class="quick-arrow">↗</span>
                    </div>

                    <h3>{{ __('E-Exam') }}</h3>

                    <p>
                        {{ __('Akses ujian dan kuis, periksa jadwal pengerjaan, dan kerjakan evaluasi pembelajaran secara online.') }}
                    </p>

                    <span class="quick-link">
                        {{ __('Masuk ke E-Exam') }}
                        <span>→</span>
                    </span>
                </a>

            </div>

            {{-- INFORMASI DAN MOTIVASI --}}
            <div class="bottom-grid">

                <section class="info-card" id="informasi-dashboard">
                    <div class="info-heading">
                        <div class="info-heading-icon">
                            <svg fill="none" stroke="currentColor"
                                 stroke-width="1.7" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 18h.01M8.2 9a4 4 0 117.6 1.7c-.8 1.1-2.8 1.8-3.3 3.3M12 22a10 10 0 110-20 10 10 0 010 20z"/>
                            </svg>
                        </div>

                        <div>
                            <h3>{{ __('Pusat Pembelajaran') }}</h3>
                            <p>{{ __('Informasi penggunaan portal siswa') }}</p>
                        </div>
                    </div>

                    <p class="info-text">
                        {{ __('Gunakan E-Class untuk mengikuti pembelajaran, membaca materi, dan mengumpulkan tugas. Kunjungi E-Exam untuk melihat ujian dan kuis yang diberikan oleh guru. Pastikan kamu selalu memperhatikan informasi dan batas waktu setiap kegiatan.') }}
                    </p>
                </section>

                <section class="info-card quote-card">
                    <div class="quote-mark">“</div>

                    <p class="quote-text">
                        {{ __('Education is the most powerful weapon which you can use to change the world.') }}
                    </p>

                    <p class="quote-author">
                        — NELSON MANDELA
                    </p>
                </section>

            </div>

            {{-- FOOTER --}}
            <footer class="dashboard-footer">
                <p>
                    METHODIST LEARNING SPACE
                    <span class="mx-2">•</span>
                    STUDENT PORTAL
                </p>
                <p class="mt-2">
                    {{ __('Belajar hari ini, berkarya untuk masa depan.') }}
                </p>
            </footer>

        </div>
    </div>
</x-app-layout>