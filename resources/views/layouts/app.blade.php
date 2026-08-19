<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Arsip Surat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased selection:bg-blue-500 selection:text-white">

<div class="flex min-h-screen relative overflow-x-hidden">
    
    <!-- BACKDROP MOBILE -->
    <div id="sidebar-backdrop" class="fixed inset-0 bg-slate-900/50 z-25 transition-opacity duration-300 opacity-0 pointer-events-none lg:hidden"></div>

    <!-- Sidebar Left Navigation (Tetap Permanen) -->
    <aside 
        id="app-sidebar"
        style="background-color: #090d16 !important;"
        class="text-slate-300 flex flex-col fixed h-full z-30 shadow-2xl border-r border-slate-800/80 w-64 -translate-x-full lg:translate-x-0 transition-transform duration-300">
        
        <!-- Brand / Logo Header -->
        <div class="h-16 px-4 border-b border-slate-800/80 flex items-center justify-between shrink-0" style="background-color: #090d16 !important;">
            <div class="flex items-center gap-3 overflow-hidden">
                <!-- IKON LOGO SURAT -->
                <div class="relative w-9 h-9 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center text-white shadow-lg shadow-blue-500/30 ring-1 ring-white/20 shrink-0">
                    <svg class="w-4 h-4 text-white drop-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-emerald-400 border-2 border-[#090d16] rounded-full shadow-sm"></span>
                </div>
                
                <!-- NAMA SISTEM -->
                <div class="flex flex-col whitespace-nowrap">
                    <span class="font-bold text-white text-sm tracking-tight font-sans">
                        Arsip<span class="text-blue-500">Surat</span>
                    </span>
                    <p class="text-[9px] text-slate-400 font-medium tracking-wide">Sistem Informasi Persuratan</p>
                </div>
            </div>

            <!-- Tombol Close khusus HP -->
            <button type="button" id="mobile-close-sidebar" class="p-1.5 text-slate-400 hover:text-white lg:hidden focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-3 py-5 space-y-1.5 overflow-y-auto overflow-x-hidden">
            <a href="{{ route('dashboard') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'hover:bg-slate-800/60 hover:text-white text-slate-400' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                <span class="whitespace-nowrap">Dashboard</span>
            </a>

            <a href="{{ route('surat-masuk.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('surat-masuk.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'hover:bg-slate-800/60 hover:text-white text-slate-400' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                <span class="whitespace-nowrap">Surat Masuk</span>
            </a>

            <a href="{{ route('surat-keluar.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('surat-keluar.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'hover:bg-slate-800/60 hover:text-white text-slate-400' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                <span class="whitespace-nowrap">Surat Keluar</span>
            </a>

            <a href="{{ route('disposisi.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('disposisi.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'hover:bg-slate-800/60 hover:text-white text-slate-400' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                <span class="whitespace-nowrap">Disposisi</span>
            </a>

            <!-- Master Data -->
            @if(auth()->user()?->isAdmin() || auth()->user()?->role === 'admin')
                <div class="pt-5 pb-2 px-3.5">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Master Data</p>
                </div>

                <a href="{{ route('kategori.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('kategori.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'hover:bg-slate-800/60 hover:text-white text-slate-400' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 11h.01M7 15h.01M11 7h8M11 11h8M11 15h8"/></svg>
                    <span class="whitespace-nowrap">Kategori Surat</span>
                </a>

                <a href="{{ route('instansi.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('instansi.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'hover:bg-slate-800/60 hover:text-white text-slate-400' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span class="whitespace-nowrap">Instansi</span>
                </a>

                <a href="{{ route('users.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('users.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'hover:bg-slate-800/60 hover:text-white text-slate-400' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span class="whitespace-nowrap">Pengguna</span>
                </a>
            @endif
        </nav>

        <!-- Footer Sidebar (Tanpa Tombol Toggle) -->
        <div class="p-4 border-t border-slate-800/80 flex items-center justify-center text-xs text-slate-500 font-medium shrink-0" style="background-color: #090d16 !important;">
            <span class="truncate">© {{ date('Y') }} Arsip Surat</span>
        </div>
    </aside>

    <!-- Right Side Content Container (Margin Left Tetap w-64 di Layar Besar) -->
    <div class="flex-1 flex flex-col min-h-screen ml-0 lg:ml-64">
        
        <!-- Sticky Top Navigation Header -->
        <header class="bg-white/80 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-20">
            <div class="flex items-center justify-between px-4 sm:px-8 py-3.5">
                
                <div class="flex items-center gap-3">
                    <!-- Tombol Hamburger khusus HP -->
                    <button type="button" id="mobile-open-sidebar" class="p-2 text-slate-600 hover:text-blue-600 hover:bg-slate-100 rounded-xl transition lg:hidden focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>

                    <h2 class="text-base sm:text-lg font-bold text-slate-800 tracking-tight truncate max-w-[180px] sm:max-w-none">@yield('title', 'Dashboard')</h2>
                </div>
                
                <!-- Profile / User Actions -->
                <div class="flex items-center gap-3 sm:gap-5">
                    <div class="flex items-center gap-3 pl-2 sm:pl-4 border-l border-slate-200">
                        <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-blue-100 border border-blue-200 flex items-center justify-center text-blue-700 font-bold text-xs sm:text-sm shadow-sm">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="text-left hidden sm:block">
                            <p class="text-sm font-bold text-slate-800 leading-tight">{{ auth()->user()->name ?? 'Pengguna' }}</p>
                            <p class="text-xs text-slate-400 font-medium mt-0.5">
                                {{ ucfirst(auth()->user()->role ?? 'User') }} &middot; {{ auth()->user()->jabatan ?? 'Staf' }}
                            </p>
                        </div>
                    </div>

                    <form method="POST" action="{{ Route::has('logout') ? route('logout') : url('/logout') }}">
                        @csrf
                        <button type="submit" class="text-xs font-semibold px-2.5 sm:px-3.5 py-2 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 transition duration-150 flex items-center gap-1.5 border border-red-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            <span class="hidden sm:inline">Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Main Workspace Area -->
        <main class="p-4 sm:p-8 flex-1">
            @yield('content')
        </main>
    </div>
</div>

<!-- Notifikasi Flash Toast -->
@if (session('success') || session('error') || session('info') || $errors->any())
    <div id="toast-notification" class="fixed top-5 right-5 z-50 flex items-center gap-3 w-full max-w-sm p-4 text-slate-700 bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl border border-slate-100 transition-all duration-300 transform translate-y-0 opacity-100" role="alert">
        @if (session('success'))
            <div class="inline-flex items-center justify-center flex-shrink-0 w-10 h-10 text-emerald-600 bg-emerald-50 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div class="flex-1 text-sm font-semibold text-slate-800">{{ session('success') }}</div>
        @elseif (session('error'))
            <div class="inline-flex items-center justify-center flex-shrink-0 w-10 h-10 text-rose-600 bg-rose-50 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            <div class="flex-1 text-sm font-semibold text-slate-800">{{ session('error') }}</div>
        @elseif (session('info'))
            <div class="inline-flex items-center justify-center flex-shrink-0 w-10 h-10 text-blue-600 bg-blue-50 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="flex-1 text-sm font-semibold text-slate-800">{{ session('info') }}</div>
        @elseif ($errors->any())
            <div class="inline-flex items-center justify-center flex-shrink-0 w-10 h-10 text-amber-600 bg-amber-50 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div class="flex-1 text-sm font-semibold text-slate-800">
                {{ $errors->first() }}
            </div>
        @endif

        <button type="button" onclick="closeToast()" class="text-slate-400 hover:text-slate-600 rounded-lg p-1 hover:bg-slate-100 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
@endif

<!-- Global Scripts -->
<script>
    function closeToast() {
        const toast = document.getElementById('toast-notification');
        if (toast) {
            toast.classList.add('opacity-0', '-translate-y-2');
            setTimeout(() => toast.remove(), 300);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.getElementById('app-sidebar');
        const backdrop = document.getElementById('sidebar-backdrop');

        function openMobileSidebar() {
            sidebar.classList.remove('-translate-x-full');
            backdrop.classList.remove('opacity-0', 'pointer-events-none');
        }

        function closeMobileSidebar() {
            sidebar.classList.add('-translate-x-full');
            backdrop.classList.add('opacity-0', 'pointer-events-none');
        }

        const mobileOpenBtn = document.getElementById('mobile-open-sidebar');
        const mobileCloseBtn = document.getElementById('mobile-close-sidebar');

        if (mobileOpenBtn) mobileOpenBtn.addEventListener('click', openMobileSidebar);
        if (mobileCloseBtn) mobileCloseBtn.addEventListener('click', closeMobileSidebar);
        if (backdrop) backdrop.addEventListener('click', closeMobileSidebar);

        // Auto Close Toast Notification
        const toast = document.getElementById('toast-notification');
        if (toast) {
            setTimeout(() => { closeToast(); }, 4500);
        }

        // Global Delete Confirmation Modal
        window.confirmDelete = function (event, message = 'Data yang dihapus tidak dapat dikembalikan!') {
            event.preventDefault();
            const form = event.target.closest('form');
            if (!form) return;

            const confirmed = window.confirm('Apakah Anda Yakin?\n\n' + message);
            if (confirmed) form.submit();
        };
    });
</script>

@stack('scripts')
</body>
</html>