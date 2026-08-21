<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Sistem Manajemen Surat') - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS & JS (Dynamic Safe Vite Loading) -->
    @if (file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @elseif (file_exists(public_path('build/manifest.json')))
        @php
            $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true) ?? [];
            $hasCss = isset($manifest['resources/css/app.css']);
            $hasJs = isset($manifest['resources/js/app.js']);
        @endphp

        @if ($hasCss && $hasJs)
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @elseif ($hasCss)
            @vite(['resources/css/app.css'])
        @elseif ($hasJs)
            @vite(['resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
        @endif
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="h-full text-slate-700 bg-slate-50 antialiased selection:bg-blue-500 selection:text-white" x-data="{ sidebarOpen: false }">

    <div class="min-h-screen flex flex-col md:flex-row">

        <!-- Mobile Backdrop -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false" 
             class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 md:hidden" 
             style="display: none;"></div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
               class="fixed md:static inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-300 transition-transform duration-300 ease-in-out md:translate-x-0 flex flex-col justify-between shrink-0">
            
            <div>
                <!-- Sidebar Header / Logo -->
                <div class="h-16 flex items-center px-6 border-b border-slate-800 gap-3">
                    <div class="w-8 h-8 rounded-xl bg-blue-600 flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-blue-500/30">
                        S
                    </div>
                    <div>
                        <span class="font-bold text-white tracking-wide text-sm block">E-Arsip Surat</span>
                        <span class="text-[10px] text-slate-400 block font-medium">Sistem Informasi Kantor</span>
                    </div>
                </div>

                <!-- Navigation Links Lengkap -->
                <nav class="p-4 space-y-1 text-xs font-semibold">
                    
                    <!-- Dashboard -->
                    <a href="{{ Route::has('dashboard') ? route('dashboard') : '#' }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white font-bold' : 'hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span>Dashboard</span>
                    </a>

                    <div class="pt-4 pb-1 px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Modul Surat</div>

                    <!-- Surat Masuk -->
                    @if(Route::has('surat-masuk.index'))
                        <a href="{{ route('surat-masuk.index') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('surat-masuk.*') ? 'bg-blue-600 text-white font-bold' : 'hover:bg-slate-800 hover:text-white' }}">
                            <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                            <span>Surat Masuk</span>
                        </a>
                    @endif

                    <!-- Surat Keluar -->
                    @if(Route::has('surat-keluar.index'))
                        <a href="{{ route('surat-keluar.index') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('surat-keluar.*') ? 'bg-blue-600 text-white font-bold' : 'hover:bg-slate-800 hover:text-white' }}">
                            <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            <span>Surat Keluar</span>
                        </a>
                    @endif

                    <!-- Disposisi -->
                    @if(Route::has('disposisi.index'))
                        <a href="{{ route('disposisi.index') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('disposisi.*') ? 'bg-blue-600 text-white font-bold' : 'hover:bg-slate-800 hover:text-white' }}">
                            <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                            <span>Disposisi</span>
                        </a>
                    @endif

                    <div class="pt-4 pb-1 px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Master Data</div>

                    <!-- Kategori Surat -->
                    @if(Route::has('kategori.index'))
                        <a href="{{ route('kategori.index') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('kategori.*') ? 'bg-blue-600 text-white font-bold' : 'hover:bg-slate-800 hover:text-white' }}">
                            <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 11h.01M7 15h.01M11 7h8M11 11h8M11 15h8"/></svg>
                            <span>Kategori Surat</span>
                        </a>
                    @endif

                    <!-- Instansi / Pengirim -->
                    @if(Route::has('instansi.index'))
                        <a href="{{ route('instansi.index') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('instansi.*') ? 'bg-blue-600 text-white font-bold' : 'hover:bg-slate-800 hover:text-white' }}">
                            <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <span>Instansi / Pengirim</span>
                        </a>
                    @endif

                    <!-- Pengguna / User -->
                    @if(Route::has('users.index'))
                        <a href="{{ route('users.index') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('users.*') ? 'bg-blue-600 text-white font-bold' : 'hover:bg-slate-800 hover:text-white' }}">
                            <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            <span>Manajemen Pengguna</span>
                        </a>
                    @endif

                </nav>
            </div>

            <!-- Footer User Info -->
            <div class="p-4 border-t border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-slate-700 flex items-center justify-center font-bold text-white text-xs border border-slate-600">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 2)) }}
                    </div>
                    <div class="truncate">
                        <p class="text-xs font-semibold text-white truncate">{{ Auth::user()->name ?? 'Administrator' }}</p>
                        <p class="text-[10px] text-slate-400 truncate">{{ Auth::user()->email ?? 'admin@mail.com' }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Workspace -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

            <!-- Top Header Navbar -->
            <header class="h-16 bg-white border-b border-slate-200/80 flex items-center justify-between px-4 sm:px-6 z-10">
                <div class="flex items-center gap-3">
                    <!-- Mobile Hamburger Button -->
                    <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 text-slate-600 hover:bg-slate-100 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <span class="text-xs font-semibold text-slate-400 hidden sm:inline-block">Sistem Informasi Pengelolaan Surat</span>
                </div>

                <!-- Right Nav Items -->
                <div class="flex items-center gap-3">
                    @if(Route::has('logout'))
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-xs font-semibold text-rose-600 hover:bg-rose-50 px-3 py-1.5 rounded-lg transition">
                                Keluar
                            </button>
                        </form>
                    @endif
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                
                <!-- Flash Message dengan Auto-Dismiss 3 Detik -->
                @if (session('success'))
                    <div x-data="{ show: true }" 
                         x-show="show" 
                         x-init="setTimeout(() => show = false, 3000)"
                         x-transition:leave="transition ease-in duration-300 transform opacity-100 scale-100"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-semibold flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button @click="show = false" class="text-emerald-500 hover:text-emerald-800 p-1 rounded-lg transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>