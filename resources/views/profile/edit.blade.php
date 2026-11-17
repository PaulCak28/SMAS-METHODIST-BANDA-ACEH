<x-app-layout>

    <x-slot name="header">
        <div class="profile-topbar">
            <div>
                <span class="topbar-eyebrow">E-CLASS / ACCOUNT</span>
                <h2>My Profile</h2>
            </div>

            <div class="topbar-icon">
                <i class="bi bi-person-circle"></i>
            </div>
        </div>
    </x-slot>

    @php
        $user = Auth::user();
        $initial = strtoupper(substr($user->name ?? 'U', 0, 1));
    @endphp

    <div class="profile-page">
        <div class="profile-wrapper">

            {{-- SOCIAL PROFILE CARD --}}
            <section class="social-profile">

                {{-- COVER --}}
                <div class="profile-cover">
                    <div class="cover-overlay"></div>

                    <div class="cover-content">
                        <span class="cover-tag">
                            <i class="bi bi-mortarboard-fill"></i>
                            SMAS METHODIST BANDA ACEH
                        </span>

                        <div class="cover-decoration cover-decoration-one"></div>
                        <div class="cover-decoration cover-decoration-two"></div>
                        <div class="cover-decoration cover-decoration-three"></div>
                    </div>
                </div>

                {{-- PROFILE IDENTITY --}}
                <div class="identity-area">

                    <div class="avatar-column">
                        <div class="profile-avatar">
                            {{ $initial }}
                        </div>
                        <span class="avatar-online"></span>
                    </div>

                    <div class="identity-content">
                        <div class="identity-main">
                            <div class="identity-name">
                                <h1>{{ $user->name }}</h1>
                                <span class="verified-badge" title="Akun E-Class">
                                    <i class="bi bi-patch-check-fill"></i>
                                </span>
                            </div>

                            <p class="identity-email">
                                <i class="bi bi-envelope"></i>
                                {{ $user->email }}
                            </p>

                            <div class="identity-badges">
                                <span class="account-badge">
                                    <i class="bi bi-person-fill"></i>
                                    E-Class Member
                                </span>

                                <span class="active-badge">
                                    <span></span>
                                    Active Account
                                </span>
                            </div>
                        </div>

                        <div class="identity-actions">
                            <a href="#profile-information" class="edit-profile-button">
                                <i class="bi bi-pencil-square"></i>
                                Edit Profile
                            </a>
                        </div>
                    </div>

                </div>

                {{-- PROFILE NAVIGATION --}}
                <div class="profile-navigation">
                    <a href="#profile-information" class="profile-nav-item active">
                        <i class="bi bi-person-lines-fill"></i>
                        <span>Personal Info</span>
                    </a>

                    <a href="#profile-password" class="profile-nav-item">
                        <i class="bi bi-shield-lock"></i>
                        <span>Security</span>
                    </a>

                    <a href="#profile-delete" class="profile-nav-item">
                        <i class="bi bi-gear"></i>
                        <span>Account Settings</span>
                    </a>
                </div>

            </section>

            {{-- INTRO --}}
            <div class="profile-welcome">
                <div>
                    <span class="welcome-label">ACCOUNT CENTER</span>
                    <h2>Pengaturan Akun</h2>
                    <p>
                        Kelola informasi pribadi dan keamanan akun
                        E-Class Anda dengan mudah.
                    </p>
                </div>

                <div class="welcome-illustration">
                    <i class="bi bi-person-gear"></i>
                </div>
            </div>

            {{-- PROFILE INFORMATION --}}
            <section class="settings-card" id="profile-information">
                <div class="settings-heading">
                    <div class="settings-icon icon-blue">
                        <i class="bi bi-person-vcard-fill"></i>
                    </div>

                    <div>
                        <h3>Informasi Profil</h3>
                        <p>Perbarui nama dan alamat email akun Anda.</p>
                    </div>
                </div>

                <div class="settings-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </section>

            {{-- PASSWORD --}}
            <section class="settings-card" id="profile-password">
                <div class="settings-heading">
                    <div class="settings-icon icon-purple">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>

                    <div>
                        <h3>Keamanan Password</h3>
                        <p>Pastikan password Anda tetap aman dan tidak mudah ditebak.</p>
                    </div>
                </div>

                <div class="settings-body">
                    @include('profile.partials.update-password-form')
                </div>
            </section>

            {{-- DELETE ACCOUNT --}}
            <section class="settings-card delete-card" id="profile-delete">
                <div class="settings-heading">
                    <div class="settings-icon icon-red">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>

                    <div>
                        <h3>Penghapusan Akun</h3>
                        <p>Kelola opsi penghapusan akun secara permanen.</p>
                    </div>
                </div>

                <div class="settings-body">
                    @include('profile.partials.delete-user-form')
                </div>
            </section>

            {{-- FOOTER --}}
            <footer class="profile-footer">
                <div class="footer-brand">
                    <i class="bi bi-mortarboard-fill"></i>
                    <span>E-CLASS</span>
                </div>

                <p>
                    SMAS Methodist Banda Aceh
                    <span class="footer-dot">•</span>
                    Learning Management System
                </p>
            </footer>

        </div>
    </div>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap');

        /* =========================================
           BASE
        ========================================= */

        .profile-page {
            min-height: 100vh;
            padding: 30px 24px 55px;
            background: #edf2f9;
            color: #172b4d;
            font-family: 'DM Sans', sans-serif;
        }

        .profile-wrapper {
            width: 100%;
            max-width: 1120px;
            margin: auto;
        }

        /* =========================================
           TOPBAR
        ========================================= */

        .profile-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
        }

        .topbar-eyebrow {
            color: #2563eb;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.5px;
        }

        .profile-topbar h2 {
            margin: 4px 0 0;
            color: #10264d;
            font-size: 21px;
            font-weight: 800;
        }

        .topbar-icon {
            display: grid;
            width: 44px;
            height: 44px;
            place-items: center;
            border-radius: 13px;
            background: #e5efff;
            color: #174ea6;
            font-size: 21px;
        }

        /* =========================================
           SOCIAL PROFILE
        ========================================= */

        .social-profile {
            overflow: hidden;
            border: 1px solid #dce6f3;
            border-radius: 22px;
            background: white;
            box-shadow: 0 9px 30px rgba(20, 48, 88, .06);
        }

        /* COVER */

        .profile-cover {
            position: relative;
            min-height: 215px;
            overflow: hidden;
            background:
                linear-gradient(115deg, #091b38, #12366f 55%, #2563b8);
        }

        .cover-overlay {
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 75% 20%, rgba(96,165,250,.28), transparent 32%),
                linear-gradient(90deg, rgba(4,18,43,.18), transparent);
        }

        .cover-content {
            position: relative;
            display: flex;
            height: 100%;
            min-height: 215px;
            align-items: flex-start;
            padding: 27px 30px;
        }

        .cover-tag {
            position: relative;
            z-index: 2;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 13px;
            border: 1px solid rgba(255,255,255,.18);
            border-radius: 30px;
            background: rgba(255,255,255,.1);
            color: #eff6ff;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .8px;
            backdrop-filter: blur(8px);
        }

        .cover-decoration {
            position: absolute;
            border: 1px solid rgba(255,255,255,.12);
            border-radius: 50%;
            pointer-events: none;
        }

        .cover-decoration-one {
            top: -165px;
            right: 8%;
            width: 390px;
            height: 390px;
        }

        .cover-decoration-two {
            top: -95px;
            right: 20%;
            width: 245px;
            height: 245px;
        }

        .cover-decoration-three {
            right: -90px;
            bottom: -280px;
            width: 480px;
            height: 480px;
        }

        /* IDENTITY */

        .identity-area {
            position: relative;
            display: flex;
            align-items: flex-end;
            gap: 22px;
            padding: 0 35px 25px;
            border-bottom: 1px solid #edf1f7;
        }

        .avatar-column {
            position: relative;
            flex-shrink: 0;
            margin-top: -67px;
        }

        .profile-avatar {
            display: grid;
            width: 132px;
            height: 132px;
            place-items: center;
            border: 6px solid white;
            border-radius: 50%;
            background: linear-gradient(135deg, #1e4e96, #3b82f6);
            color: white;
            font-size: 48px;
            font-weight: 800;
            box-shadow: 0 5px 20px rgba(15,49,100,.15);
        }

        .avatar-online {
            position: absolute;
            right: 11px;
            bottom: 12px;
            width: 19px;
            height: 19px;
            border: 3px solid white;
            border-radius: 50%;
            background: #16a36a;
        }

        .identity-content {
            display: flex;
            flex: 1;
            min-width: 0;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px;
        }

        .identity-main {
            min-width: 0;
            padding-top: 14px;
        }

        .identity-name {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }

        .identity-name h1 {
            overflow-wrap: anywhere;
            margin: 0;
            color: #10264d;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -.6px;
        }

        .verified-badge {
            color: #2875df;
            font-size: 17px;
        }

        .identity-email {
            overflow-wrap: anywhere;
            display: flex;
            align-items: center;
            gap: 7px;
            margin: 6px 0 12px;
            color: #8291a7;
            font-size: 12px;
        }

        .identity-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .account-badge,
        .active-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 30px;
            font-size: 10px;
            font-weight: 700;
        }

        .account-badge {
            background: #eaf2ff;
            color: #2159a7;
        }

        .active-badge {
            background: #e9f9f1;
            color: #16845c;
        }

        .active-badge span {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #16a36a;
        }

        .identity-actions {
            flex-shrink: 0;
        }

        .edit-profile-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 16px;
            border: 1px solid #dce6f3;
            border-radius: 10px;
            background: white;
            color: #174ea6;
            font-size: 11px;
            font-weight: 800;
            text-decoration: none;
            transition: .2s ease;
        }

        .edit-profile-button:hover {
            border-color: #2563eb;
            background: #f1f6ff;
            transform: translateY(-2px);
        }

        /* NAVIGATION */

        .profile-navigation {
            display: flex;
            align-items: stretch;
            gap: 8px;
            padding: 0 35px;
        }

        .profile-nav-item {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 58px;
            padding: 0 17px;
            color: #8291a7;
            font-size: 11px;
            font-weight: 700;
            text-decoration: none;
            transition: .2s ease;
        }

        .profile-nav-item:hover,
        .profile-nav-item.active {
            color: #174ea6;
        }

        .profile-nav-item.active::after {
            position: absolute;
            right: 12px;
            bottom: 0;
            left: 12px;
            height: 3px;
            border-radius: 5px 5px 0 0;
            background: #2563eb;
            content: "";
        }

        /* =========================================
           INTRO
        ========================================= */

        .profile-welcome {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin: 32px 0 20px;
        }

        .welcome-label {
            color: #2563eb;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.4px;
        }

        .profile-welcome h2 {
            margin: 5px 0;
            color: #10264d;
            font-size: 22px;
            font-weight: 800;
        }

        .profile-welcome p {
            margin: 0;
            color: #8291a7;
            font-size: 12px;
            line-height: 1.7;
        }

        .welcome-illustration {
            display: grid;
            width: 53px;
            height: 53px;
            flex-shrink: 0;
            place-items: center;
            border-radius: 15px;
            background: #dceaff;
            color: #174ea6;
            font-size: 24px;
        }

        /* =========================================
           SETTINGS CARDS
        ========================================= */

        .settings-card {
            scroll-margin-top: 25px;
            margin-bottom: 20px;
            padding: 26px 29px;
            border: 1px solid #dce6f3;
            border-radius: 19px;
            background: white;
            box-shadow: 0 7px 24px rgba(20,48,88,.045);
        }

        .settings-heading {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 24px;
            padding-bottom: 19px;
            border-bottom: 1px solid #edf1f7;
        }

        .settings-icon {
            display: grid;
            width: 46px;
            height: 46px;
            flex-shrink: 0;
            place-items: center;
            border-radius: 13px;
            font-size: 19px;
        }

        .icon-blue {
            background: #e8f1ff;
            color: #2563eb;
        }

        .icon-purple {
            background: #f0eaff;
            color: #7952d8;
        }

        .icon-red {
            background: #fff0f0;
            color: #dc4444;
        }

        .settings-heading h3 {
            margin: 0;
            color: #10264d;
            font-size: 15px;
            font-weight: 800;
        }

        .settings-heading p {
            margin: 5px 0 0;
            color: #8795a9;
            font-size: 11px;
            line-height: 1.6;
        }

        .settings-body,
        .settings-body form {
            width: 100%;
            max-width: none !important;
            min-width: 0;
        }

        /* =========================================
           BREEZE FORM STYLING
        ========================================= */

        .settings-body label {
            color: #344968;
            font-size: 12px;
            font-weight: 700;
        }

        .settings-body input:not([type="checkbox"]):not([type="radio"]),
        .settings-body select,
        .settings-body textarea {
            width: 100%;
            max-width: 100%;
            border: 1px solid #dce5f1;
            border-radius: 10px;
            background: #f8faff;
            color: #19365e;
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            transition: .2s ease;
        }

        .settings-body input:not([type="checkbox"]):not([type="radio"]) {
            min-height: 44px;
            padding: 10px 13px;
        }

        .settings-body textarea {
            padding: 12px 13px;
        }

        .settings-body input:focus,
        .settings-body textarea:focus,
        .settings-body select:focus {
            border-color: #3b82f6;
            outline: none;
            background: white;
            box-shadow: 0 0 0 3px rgba(59,130,246,.1);
        }

        .settings-body button[type="submit"],
        .settings-body button[type="button"] {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            min-height: 41px;
            padding: 10px 18px;
            border: 0;
            border-radius: 10px;
            background: linear-gradient(120deg, #174ea6, #2563eb);
            color: white;
            font-family: 'DM Sans', sans-serif;
            font-size: 12px;
            font-weight: 700;
            transition: .2s ease;
            cursor: pointer;
        }

        .settings-body button[type="submit"]:hover,
        .settings-body button[type="button"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(37,99,235,.2);
        }

        .settings-body button:disabled {
            opacity: .6;
            cursor: not-allowed;
        }

        .settings-body .text-gray-600,
        .settings-body .text-gray-500 {
            color: #8291a7 !important;
        }

        .settings-body .text-sm {
            font-size: 12px;
        }

        .settings-body .text-red-600 {
            color: #dc4444 !important;
        }

        .settings-body .text-green-600 {
            color: #15966a !important;
        }

        .delete-card {
            border-color: #f2dede;
        }

        .delete-card .settings-heading {
            border-color: #f6e5e5;
        }

        .delete-card .settings-body button[type="submit"] {
            background: #dc4444;
        }

        .delete-card .settings-body button[type="submit"]:hover {
            background: #c92f2f;
            box-shadow: 0 6px 15px rgba(220,68,68,.17);
        }

        /* =========================================
           FOOTER
        ========================================= */

        .profile-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            padding: 20px 4px 0;
        }

        .footer-brand {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #174ea6;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: .8px;
        }

        .profile-footer p {
            margin: 0;
            color: #8795a9;
            font-size: 10px;
        }

        .footer-dot {
            padding: 0 5px;
            color: #2563eb;
        }

        /* =========================================
           TABLET
        ========================================= */

        @media (max-width: 900px) {
            .identity-area {
                padding-right: 25px;
                padding-left: 25px;
                gap: 17px;
            }

            .profile-navigation {
                padding-right: 20px;
                padding-left: 20px;
            }

            .profile-avatar {
                width: 115px;
                height: 115px;
            }

            .identity-name h1 {
                font-size: 21px;
            }
        }

        /* =========================================
           MOBILE
        ========================================= */

        @media (max-width: 640px) {
            .profile-page {
                padding: 18px 12px 35px;
            }

            .profile-topbar h2 {
                font-size: 19px;
            }

            .social-profile {
                border-radius: 17px;
            }

            .profile-cover,
            .cover-content {
                min-height: 155px;
            }

            .cover-content {
                padding: 19px 17px;
            }

            .cover-tag {
                max-width: 100%;
                font-size: 8px;
                letter-spacing: .4px;
            }

            .identity-area {
                display: block;
                padding: 0 18px 20px;
            }

            .avatar-column {
                width: fit-content;
                margin-top: -48px;
            }

            .profile-avatar {
                width: 96px;
                height: 96px;
                border-width: 5px;
                font-size: 37px;
            }

            .avatar-online {
                right: 7px;
                bottom: 8px;
                width: 16px;
                height: 16px;
            }

            .identity-content {
                display: flex;
                align-items: flex-start;
                flex-direction: column;
                gap: 15px;
            }

            .identity-main {
                width: 100%;
                padding-top: 10px;
            }

            .identity-name h1 {
                font-size: 21px;
            }

            .identity-email {
                font-size: 11px;
            }

            .identity-actions,
            .edit-profile-button {
                width: 100%;
            }

            .edit-profile-button {
                min-height: 40px;
            }

            .profile-navigation {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 0;
                padding: 0 7px;
            }

            .profile-nav-item {
                min-height: 55px;
                flex-direction: column;
                gap: 5px;
                padding: 7px 3px;
                font-size: 9px;
                text-align: center;
            }

            .profile-nav-item i {
                font-size: 16px;
            }

            .profile-nav-item.active::after {
                right: 8px;
                left: 8px;
            }

            .profile-welcome {
                margin: 25px 2px 17px;
            }

            .profile-welcome h2 {
                font-size: 19px;
            }

            .profile-welcome p {
                max-width: 280px;
                font-size: 11px;
            }

            .welcome-illustration {
                width: 43px;
                height: 43px;
                font-size: 20px;
            }

            .settings-card {
                padding: 20px 16px;
                border-radius: 15px;
            }

            .settings-heading {
                align-items: flex-start;
                gap: 11px;
                margin-bottom: 20px;
                padding-bottom: 16px;
            }

            .settings-icon {
                width: 40px;
                height: 40px;
                font-size: 17px;
            }

            .settings-heading h3 {
                font-size: 13px;
            }

            .settings-heading p {
                font-size: 10px;
            }

            .settings-body {
                overflow-wrap: anywhere;
            }

            .settings-body button[type="submit"],
            .settings-body button[type="button"] {
                max-width: 100%;
            }

            .profile-footer {
                align-items: flex-start;
                flex-direction: column;
            }
        }

        /* =========================================
           SMALL MOBILE
        ========================================= */

        @media (max-width: 380px) {
            .profile-page {
                padding-right: 9px;
                padding-left: 9px;
            }

            .identity-name h1 {
                font-size: 18px;
            }

            .profile-nav-item {
                font-size: 8px;
            }

            .settings-card {
                padding: 17px 13px;
            }

            .identity-badges {
                gap: 5px;
            }

            .account-badge,
            .active-badge {
                padding: 6px 8px;
                font-size: 9px;
            }
        }

        /* REDUCE MOTION ACCESSIBILITY */

        @media (prefers-reduced-motion: reduce) {
            .profile-page *,
            .profile-page *::before,
            .profile-page *::after {
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
            }
        }
    </style>

</x-app-layout>