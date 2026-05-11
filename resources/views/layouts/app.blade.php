<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' — ' : '' }}FarmTech MIS</title>
    <meta name="description" content="FarmTech MIS — Agriculture Management Information System for SHGs, FPGs and Farmers.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Alpine.js for dropdown interactivity --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-gray-50">

<div class="flex h-screen overflow-hidden">

    {{-- ══════════════════════════════════════════════════════════════
         SIDEBAR — Fixed left, green gradient
    ═══════════════════════════════════════════════════════════════ --}}
    <aside id="sidebar"
           class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-farm-700 to-farm-600
                  flex flex-col shadow-xl transform -translate-x-full lg:translate-x-0 transition-transform duration-300">

        {{-- Logo / Brand --}}
        <div class="flex items-center gap-3 px-5 py-5 border-b border-white/10">
            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/>
                </svg>
            </div>
            <div>
                <p class="text-white font-bold text-base leading-tight">FarmTech MIS</p>
                <p class="text-green-200 text-xs">Agriculture Portal</p>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-0.5">

            {{-- Dashboard --}}
            <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isOfficer() ? route('officer.dashboard') : route('farmer.dashboard')) }}"
               class="sidebar-link {{ request()->routeIs('*.dashboard') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 7a4 4 0 014-4h10a4 4 0 014 4v10a4 4 0 01-4 4H7a4 4 0 01-4-4V7z"/>
                </svg>
                Dashboard
            </a>

            {{-- Farmers --}}
            @if(auth()->user()->isAdmin() || auth()->user()->isOfficer())
            <a href="{{ route('farmers.index') }}"
               class="sidebar-link {{ request()->routeIs('farmers.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Farmers
            </a>
            @endif

            {{-- Lands & Crops (Admin / Officer) --}}
            @if(auth()->user()->isAdmin() || auth()->user()->isOfficer())
            <p class="px-4 pt-4 pb-1 text-xs font-semibold text-green-300 uppercase tracking-wider">Agriculture</p>
            <a href="{{ route('lands.index') }}"
               class="sidebar-link {{ request()->routeIs('lands.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                </svg>
                Land Records
            </a>
            <a href="{{ route('crops.index') }}"
               class="sidebar-link {{ request()->routeIs('crops.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                Crops
            </a>
            <a href="{{ route('crop-history.index') }}"
               class="sidebar-link {{ request()->routeIs('crop-history.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
                </svg>
                Crop History
            </a>
            @endif

            {{-- SHG / FPG --}}
            @if(auth()->user()->isAdmin() || auth()->user()->isOfficer())
            <p class="px-4 pt-4 pb-1 text-xs font-semibold text-green-300 uppercase tracking-wider">Groups</p>
            <a href="{{ route('shgs.index') }}"
               class="sidebar-link {{ request()->routeIs('shgs.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                SHG / FPG
            </a>
            @endif

            {{-- Schemes --}}
            <p class="px-4 pt-4 pb-1 text-xs font-semibold text-green-300 uppercase tracking-wider">Schemes</p>
            <a href="{{ route('schemes.index') }}"
               class="sidebar-link {{ request()->routeIs('schemes.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                Schemes
            </a>
            <a href="{{ route('applications.index') }}"
               class="sidebar-link {{ request()->routeIs('applications.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                {{ auth()->user()->isFarmer() ? 'My Applications' : 'Applications' }}
            </a>

            {{-- Reports (Admin + Officer) --}}
            @if(auth()->user()->isAdmin() || auth()->user()->isOfficer())
            <p class="px-4 pt-4 pb-1 text-xs font-semibold text-green-300 uppercase tracking-wider">Reports</p>
            <a href="{{ route('reports.index') }}"
               class="sidebar-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Analytics
            </a>
            @endif

        </nav>

        {{-- Logged-in User Info at Bottom --}}
        <div class="border-t border-white/10 p-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                    <span class="text-white text-sm font-bold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                    <p class="text-green-300 text-xs capitalize">{{ auth()->user()->role }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Logout"
                            class="text-green-200 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Sidebar Overlay for Mobile --}}
    <div id="sidebar-overlay"
         class="fixed inset-0 bg-black/50 z-40 lg:hidden hidden"
         onclick="closeSidebar()"></div>

    {{-- ══════════════════════════════════════════════════════════════
         MAIN CONTENT AREA
    ═══════════════════════════════════════════════════════════════ --}}
    <div class="flex-1 flex flex-col min-w-0 lg:pl-64">

        {{-- Top Navigation Bar --}}
        <header class="sticky top-0 z-30 bg-white border-b border-gray-200 shadow-sm">
            <div class="flex items-center justify-between h-16 px-4 sm:px-6">

                {{-- Left: Mobile hamburger + Page title --}}
                <div class="flex items-center gap-3">
                    <button onclick="toggleSidebar()"
                            class="lg:hidden p-2 rounded-md text-gray-500 hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <div>
                        <h1 class="text-lg font-semibold text-gray-800">{{ $header ?? 'Dashboard' }}</h1>
                        @isset($breadcrumb)
                        <p class="text-xs text-gray-500">{{ $breadcrumb }}</p>
                        @endisset
                    </div>
                </div>

                {{-- Right: Notifications + User --}}
                <div class="flex items-center gap-2">

                    {{-- Notification Bell with Dropdown --}}
                    @php
                        $unreadCount   = auth()->user()->unreadNotifications->count();
                        $recentNotifs  = auth()->user()->notifications()->latest()->limit(5)->get();
                    @endphp
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open"
                                class="relative p-2 rounded-full text-gray-500 hover:bg-gray-100 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            @if($unreadCount > 0)
                            <span class="absolute top-1 right-1 w-4 h-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center leading-none font-bold animate-pulse">
                                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                            </span>
                            @endif
                        </button>

                        {{-- Dropdown Panel --}}
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden"
                             style="display:none;">

                            {{-- Dropdown Header --}}
                            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 bg-gray-50">
                                <span class="text-sm font-semibold text-gray-700">
                                    🔔 Notifications
                                    @if($unreadCount > 0)
                                    <span class="ml-1 text-xs font-bold text-white bg-red-500 px-1.5 py-0.5 rounded-full">
                                        {{ $unreadCount }}
                                    </span>
                                    @endif
                                </span>
                                @if($unreadCount > 0)
                                <form method="POST" action="{{ route('notifications.markAllRead') }}">
                                    @csrf
                                    <button type="submit" class="text-xs text-farm-600 hover:text-farm-800 font-medium">
                                        Mark all read
                                    </button>
                                </form>
                                @endif
                            </div>

                            {{-- Recent Notifications --}}
                            <div class="max-h-72 overflow-y-auto">
                                @forelse($recentNotifs as $notif)
                                @php
                                    $d = $notif->data;
                                    $isRead = $notif->read_at !== null;
                                    $colorDot = match($d['color'] ?? 'blue') {
                                        'green' => 'bg-green-400',
                                        'red'   => 'bg-red-400',
                                        default => 'bg-blue-400',
                                    };
                                @endphp
                                <a href="{{ route('notifications.read', $notif->id) }}"
                                   class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 transition-colors border-b border-gray-50
                                          {{ !$isRead ? 'bg-blue-50/40' : '' }}">
                                    <div class="text-lg shrink-0 mt-0.5">{{ $d['icon'] ?? '🔔' }}</div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-semibold text-gray-800 leading-snug truncate">
                                            {{ $d['title'] ?? 'Notification' }}
                                        </p>
                                        <p class="text-xs text-gray-500 truncate mt-0.5">{{ $d['message'] ?? '' }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $notif->created_at->diffForHumans() }}</p>
                                    </div>
                                    @if(!$isRead)
                                    <span class="w-2 h-2 rounded-full {{ $colorDot }} shrink-0 mt-1.5"></span>
                                    @endif
                                </a>
                                @empty
                                <div class="px-4 py-8 text-center text-sm text-gray-400">
                                    No notifications yet.
                                </div>
                                @endforelse
                            </div>

                            {{-- View All --}}
                            <a href="{{ route('notifications.index') }}"
                               class="block text-center text-xs font-medium text-farm-600 hover:text-farm-800 py-3 border-t border-gray-100 bg-gray-50 transition-colors">
                                View all notifications →
                            </a>
                        </div>
                    </div>

                    {{-- User Avatar --}}
                    <div class="flex items-center gap-2 pl-2 border-l border-gray-200">
                        <div class="w-8 h-8 rounded-full bg-farm-600 flex items-center justify-center">
                            <span class="text-white text-xs font-bold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                        </div>
                        <span class="hidden sm:block text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
                    </div>
                </div>
            </div>
        </header>

        {{-- Flash Messages --}}
        @if(session('success'))
        <div class="mx-4 sm:mx-6 mt-4">
            <div class="flex items-center gap-3 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">
                <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        </div>
        @endif
        @if(session('error'))
        <div class="mx-4 sm:mx-6 mt-4">
            <div class="flex items-center gap-3 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm">
                <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('error') }}
            </div>
        </div>
        @endif

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto p-4 sm:p-6">
            {{ $slot }}
        </main>

        {{-- Footer --}}
        <footer class="bg-white border-t border-gray-100 px-6 py-3">
            <p class="text-xs text-gray-400 text-center">
                © {{ date('Y') }} FarmTech MIS — Agriculture Management Information System
            </p>
        </footer>
    </div>
</div>

{{-- Sidebar Toggle Script --}}
<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('-translate-x-full');
    document.getElementById('sidebar-overlay').classList.toggle('hidden');
}
function closeSidebar() {
    document.getElementById('sidebar').classList.add('-translate-x-full');
    document.getElementById('sidebar-overlay').classList.add('hidden');
}
</script>

{{-- Page-level scripts (charts, etc.) --}}
@stack('scripts')

</body>
</html>
