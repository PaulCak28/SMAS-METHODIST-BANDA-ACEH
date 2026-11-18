@php
    $user = auth()->user();
    $roleLabel = $user?->roleLabel() ?? 'Portal Akademik';
    $navigation = match ($user?->role) {
        'guru' => [
            ['label' => 'Dashboard', 'route' => 'guru.dashboard', 'active' => 'guru.dashboard', 'icon' => 'dashboard'],
            ['label' => 'E-Class', 'route' => 'guru.classes.index', 'active' => 'guru.classes.*', 'icon' => 'class'],
            ['label' => 'E-Exam', 'route' => 'guru.exams.index', 'active' => 'guru.exams.*', 'icon' => 'exam'],
        ],
        'siswa' => [
            ['label' => 'Dashboard', 'route' => 'siswa.dashboard', 'active' => 'siswa.dashboard', 'icon' => 'dashboard'],
            ['label' => 'E-Class', 'route' => 'siswa.classes.index', 'active' => 'siswa.classes.*', 'icon' => 'class'],
            ['label' => 'E-Exam', 'route' => 'siswa.exams.index', 'active' => 'siswa.exams.*', 'icon' => 'exam'],
        ],
        'admin' => [
            ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'active' => 'admin.dashboard', 'icon' => 'dashboard'],
        ],
        default => [],
    };
    $portalHomeRoute = $navigation[0]['route'] ?? 'home';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $roleLabel.' | SMAS Methodist')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=2">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="font-sans antialiased">
    <div x-data="{ sidebarOpen: false }" @keydown.escape.window="sidebarOpen = false" class="min-h-screen bg-[#f7f2e8] text-[#263747]">
        <div
            x-cloak
            x-show="sidebarOpen"
            x-transition.opacity
            class="fixed inset-0 z-40 bg-[#0e172a]/60 lg:hidden"
            @click="sidebarOpen = false"
            aria-hidden="true"
        ></div>

        <aside
            class="fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col bg-[#101C35] text-white shadow-2xl transition-transform duration-300 lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            aria-label="Navigasi portal"
        >
            <a href="{{ route($portalHomeRoute) }}" class="flex items-center gap-3 border-b border-white/10 px-6 py-5">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white p-1.5">
                    <img src="{{ asset('images/methodist-logo.png') }}" alt="" class="h-full w-full object-contain">
                </span>
                <span class="min-w-0">
                    <span class="block truncate text-sm font-extrabold tracking-tight">SMAS Methodist</span>
                    <span class="mt-0.5 block text-[10px] font-semibold uppercase tracking-[.18em] text-[#8eadd0]">Banda Aceh</span>
                </span>
            </a>

            <div class="px-5 pt-6">
                <p class="px-3 text-[10px] font-bold uppercase tracking-[.2em] text-slate-400">Menu {{ $roleLabel }}</p>
                <nav class="mt-3 space-y-1.5">
                    @foreach ($navigation as $item)
                        <a
                            href="{{ route($item['route']) }}"
                            @if (request()->routeIs($item['active'])) aria-current="page" @endif
                            @click="sidebarOpen = false"
                            class="group flex items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-semibold transition
                                {{ request()->routeIs($item['active']) ? 'bg-[#8eadd0]/20 text-white ring-1 ring-inset ring-[#8eadd0]/30' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}"
                        >
                            <svg class="h-5 w-5 shrink-0 {{ request()->routeIs($item['active']) ? 'text-[#b9d1ea]' : 'text-slate-400 group-hover:text-[#b9d1ea]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                @if ($item['icon'] === 'dashboard')
                                    <rect x="3.5" y="3.5" width="7" height="7" rx="1.5"/>
                                    <rect x="13.5" y="3.5" width="7" height="7" rx="1.5"/>
                                    <rect x="3.5" y="13.5" width="7" height="7" rx="1.5"/>
                                    <rect x="13.5" y="13.5" width="7" height="7" rx="1.5"/>
                                @elseif ($item['icon'] === 'class')
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 5.5A2.5 2.5 0 016.5 3H20v17H6.5A2.5 2.5 0 014 17.5v-12zM4 16a2 2 0 012-2h14M8 7h8M8 10h6"/>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 3.75h7l4 4V20a1.25 1.25 0 01-1.25 1.25h-9.5A1.25 1.25 0 016 20V5a1.25 1.25 0 011.25-1.25zM14 4v4h4M9 13h6M9 16.5h6"/>
                                @endif
                            </svg>
                            <span>{{ $item['label'] }}</span>
                            @if (request()->routeIs($item['active']))
                                <span class="ml-auto h-1.5 w-1.5 rounded-full bg-[#b9d1ea]"></span>
                            @endif
                        </a>
                    @endforeach

                    @if ($user)
                        <a
                            href="{{ route('profile.edit') }}"
                            @if (request()->routeIs('profile.*')) aria-current="page" @endif
                            @click="sidebarOpen = false"
                            class="group flex items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-semibold transition
                                {{ request()->routeIs('profile.*') ? 'bg-[#8eadd0]/20 text-white ring-1 ring-inset ring-[#8eadd0]/30' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}"
                        >
                            <svg class="h-5 w-5 shrink-0 text-slate-400 group-hover:text-[#b9d1ea]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <circle cx="12" cy="8" r="3.25"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5.5 20a6.5 6.5 0 0113 0"/>
                            </svg>
                            <span>Profil</span>
                        </a>
                    @endif
                </nav>
            </div>

            <div class="mt-auto border-t border-white/10 p-4">
                @if ($user)
                    <div class="flex items-center gap-3 rounded-xl bg-white/5 p-3">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#8eadd0] font-bold text-[#101C35]">
                            {{ strtoupper(mb_substr($user->name, 0, 1)) }}
                        </span>
                        <span class="min-w-0">
                            <span class="block truncate text-sm font-bold">{{ $user->name }}</span>
                            <span class="block text-xs text-slate-400">{{ $roleLabel }}</span>
                        </span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="mt-3">
                        @csrf
                        <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-slate-300 transition hover:bg-rose-500/10 hover:text-rose-200">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 5H6.5A1.5 1.5 0 005 6.5v11A1.5 1.5 0 006.5 19H10m4-3 4-4-4-4m4 4H9"/>
                            </svg>
                            Keluar
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block rounded-xl bg-[#8eadd0] px-4 py-2.5 text-center text-sm font-bold text-[#101C35]">
                        Masuk Portal
                    </a>
                @endif
            </div>
        </aside>

        <div class="min-h-screen lg:pl-72">
            <header class="sticky top-0 z-30 flex min-h-[72px] items-center justify-between gap-4 border-b border-slate-200 bg-white/95 px-4 shadow-sm backdrop-blur sm:px-6 lg:px-8">
                <div class="flex min-w-0 items-center gap-3">
                    <button
                        type="button"
                        @click="sidebarOpen = true"
                        aria-label="Buka navigasi"
                        :aria-expanded="sidebarOpen"
                        class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-slate-200 text-[#101C35] hover:bg-slate-50 lg:hidden"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold uppercase tracking-[.18em] text-[#267bb5]">SMAS Methodist · {{ $roleLabel }}</p>
                        <p class="truncate text-sm font-bold text-[#101C35] sm:text-base">Portal Akademik</p>
                    </div>
                </div>
                @if ($user)
                    <div class="hidden items-center gap-3 text-right sm:flex">
                        <span class="text-sm font-semibold text-slate-600">{{ $user->name }}</span>
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#e8f1f8] font-bold text-[#17365d]">
                            {{ strtoupper(mb_substr($user->name, 0, 1)) }}
                        </span>
                    </div>
                @endif
            </header>

            @isset($header)
                <div class="mx-auto max-w-7xl px-4 pt-6 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            @endisset

            <main class="mx-auto w-full max-w-[1600px] px-4 py-6 sm:px-6 lg:px-8">
                {{ $slot }}
            </main>

            <footer class="border-t border-slate-200 bg-white px-4 py-4 text-center text-xs text-slate-500 sm:px-6 lg:px-8">
                &copy; {{ date('Y') }} SMAS Methodist Banda Aceh · Portal Akademik
            </footer>
        </div>
    </div>
    <script>
        window.addEventListener('pageshow', function (event) {
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script>
</body>
</html>
