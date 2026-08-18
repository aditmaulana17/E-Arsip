<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Arsip Surat</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased selection:bg-blue-500 selection:text-white">

<div class="flex min-h-screen relative overflow-x-hidden">
    <!-- Sidebar Left Navigation (Background Dark Navy #090d16 disesuaikan seperti Login) -->
    <aside 
        id="app-sidebar"
        class="bg-[#090d16] text-slate-300 flex flex-col fixed h-full z-30 shadow-2xl border-r border-slate-800/60 transition-all duration-300">
        
        <!-- Brand / Logo Header Archive -->
        <div class="h-16 px-4 border-b border-slate-800/60 flex items-center justify-between">
            <div id="sidebar-brand" class="flex items-center gap-3 overflow-hidden mx-auto w-full">
                
                <!-- IKON LOGO SURAT (Pendar Biru Khas Halaman Login) -->
                <button type="button" data-sidebar-toggle class="relative w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center text-white shadow-lg shadow-blue-500/30 ring-1 ring-white/20 shrink-0 focus:outline-none" title="Toggle Sidebar">
                    <svg class="w-5 h-5 text-white drop-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span class="absolute -top-1 -right-1 w-3 h-3 bg-emerald-400 border-2 border-[#090d16] rounded-full shadow-sm"></span>
                </button>
                
                <!-- NAMA SISTEM (ArsipSurat) -->
                <div class="flex flex-col whitespace-nowrap" data-sidebar-label>
                    <span class="font-bold text-white text-base tracking-tight font-sans">
                        Arsip<span class="text-blue-500">Surat</span>
                    </span>
                    <p class="text-[10px] text-slate-400 font-medium tracking-wide">Sistem Informasi Persuratan</p>
                </div>
            </div>

            <!-- IKON TOGGLE SIDEBAR << -->
            <button data-sidebar-label 
                    data-sidebar-collapse 
                    class="p-1.5 text-slate-400 hover:text-white hover:bg-slate-800/60 rounded-lg transition duration-150 focus:outline-none shrink-0"
                    title="Mengecilkan Sidebar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                </svg>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-3 py-5 space-y-1.5 overflow-y-auto">
            <a href="{{ route('dashboard') }}" 
               title="Dashboard"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'hover:bg-slate-800/50 hover:text-white text-slate-400' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                <span data-sidebar-label class="whitespace-nowrap">Dashboard</span>
            </a>

            <a href="{{ route('surat-masuk.index') }}" 
               title="Surat Masuk"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('surat-masuk.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'hover:bg-slate-800/50 hover:text-white text-slate-400' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                <span data-sidebar-label class="whitespace-nowrap">Surat Masuk</span>
            </a>

            <a href="{{ route('surat-keluar.index') }}" 
               title="Surat Keluar"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('surat-keluar.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'hover:bg-slate-800/50 hover:text-white text-slate-400' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                <span data-sidebar-label class="whitespace-nowrap">Surat Keluar</span>
            </a>

            <a href="{{ route('disposisi.index') }}" 
               title="Disposisi"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('disposisi.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'hover:bg-slate-800/50 hover:text-white text-slate-400' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                <span data-sidebar-label class="whitespace-nowrap">Disposisi</span>
            </a>

            <!-- Master Data -->
            @if(auth()->user()?->isAdmin() || auth()->user()?->role === 'admin')
                <div class="pt-5 pb-2 px-3.5" data-sidebar-label>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Master Data</p>
                </div>

                <a href="{{ route('kategori.index') }}" 
                   title="Kategori Surat"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('kategori.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'hover:bg-slate-800/50 hover:text-white text-slate-400' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 11h.01M7 15h.01M11 7h8M11 11h8M11 15h8"/></svg>
                    <span data-sidebar-label class="whitespace-nowrap">Kategori Surat</span>
                </a>

                <a href="{{ route('instansi.index') }}" 
                   title="Instansi"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('instansi.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'hover:bg-slate-800/50 hover:text-white text-slate-400' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span data-sidebar-label class="whitespace-nowrap">Instansi</span>
                </a>

                <a href="{{ route('users.index') }}" 
                   title="Pengguna"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('users.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'hover:bg-slate-800/50 hover:text-white text-slate-400' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span data-sidebar-label class="whitespace-nowrap">Pengguna</span>
                </a>
            @endif
        </nav>

        <!-- Footer Sidebar -->
        <div class="p-4 border-t border-slate-800/60 text-center text-xs text-slate-500 font-medium whitespace-nowrap">
            <span data-sidebar-label>© {{ date('Y') }} Arsip Surat. All rights reserved.</span>
            <span data-sidebar-collapsed hidden>&copy;</span>
        </div>
    </aside>

    <!-- Right Side Content Container -->
    <div id="app-content" class="flex-1 flex flex-col min-h-screen transition-all duration-300 ml-64">
        
        <!-- Sticky Top Navigation Header -->
        <header class="bg-white/80 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-20">
            <div class="flex items-center justify-between px-8 py-3.5">
                
                <div class="flex items-center gap-3">
                    <button data-sidebar-collapsed 
                            data-sidebar-expand 
                            class="p-2 text-slate-600 hover:text-blue-600 hover:bg-slate-100 rounded-xl transition duration-150 focus:outline-none"
                            title="Membuka Sidebar">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                        </svg>
                    </button>

                    <h2 class="text-lg font-bold text-slate-800 tracking-tight">@yield('title', 'Dashboard')</h2>
                </div>
                
                <!-- Profile / User Actions -->
                <div class="flex items-center gap-5">
                    <div class="flex items-center gap-3 pl-4 border-l border-slate-200">
                        <div class="w-9 h-9 rounded-full bg-blue-100 border border-blue-200 flex items-center justify-center text-blue-700 font-bold text-sm shadow-sm">
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
                        <button type="submit" class="text-xs font-semibold px-3.5 py-2 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 transition duration-150 flex items-center gap-1.5 border border-red-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            <span>Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Main Workspace Area -->
        <main class="p-8 flex-1">
            @yield('content')
        </main>
    </div>
</div>

<!-- Lightweight global UI scripts -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('app-sidebar');
    const content = document.getElementById('app-content');
    const labels = document.querySelectorAll('[data-sidebar-label]');
    const brand = document.getElementById('sidebar-brand');
    const collapsed = document.querySelectorAll('[data-sidebar-collapsed]');
    const storageKey = 'sidebarOpen';

    function setSidebar(open, save = true) {
        if (!sidebar || !content) return;
        sidebar.classList.toggle('w-64', open);
        sidebar.classList.toggle('w-20', !open);
        content.classList.toggle('ml-64', open);
        content.classList.toggle('ml-20', !open);
        labels.forEach(el => el.hidden = !open);
        if (brand) brand.classList.toggle('w-full', open);
        collapsed.forEach(el => el.hidden = open);
        if (save) localStorage.setItem(storageKey, String(open));
    }

    const saved = localStorage.getItem(storageKey);
    setSidebar(saved === null ? true : saved === 'true', false);

    document.querySelectorAll('[data-sidebar-toggle]').forEach(btn => {
        btn.addEventListener('click', () => {
            const open = sidebar.classList.contains('w-64');
            setSidebar(!open);
        });
    });
    document.querySelectorAll('[data-sidebar-collapse]').forEach(btn => {
        btn.addEventListener('click', () => setSidebar(false));
    });
    document.querySelectorAll('[data-sidebar-expand]').forEach(btn => {
        btn.addEventListener('click', () => setSidebar(true));
    });

    // Lightweight toast: no third-party notification library required.
    const flash = [
        @json(session('success')),
        @json(session('error')),
        @json($errors->any() ? 'Terjadi kesalahan input!' : null)
    ].filter(Boolean)[0];

    if (flash) {
        const toast = document.createElement('div');
        toast.textContent = flash;
        toast.setAttribute('role', 'status');
        toast.style.cssText = 'position:fixed;right:1.25rem;top:1.25rem;z-index:9999;max-width:360px;padding:.8rem 1rem;border-radius:.75rem;background:#fff;border:1px solid #e2e8f0;box-shadow:0 10px 30px rgba(15,23,42,.12);font-size:.875rem;font-weight:600;color:#334155;';
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3500);
    }

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