<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Arsip Surat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { brand: { 50:'#eef4ff',100:'#dbe6fe',500:'#3457d5',600:'#2a45ab',700:'#233a8c' } } } } }
    </script>
</head>
<body class="bg-slate-100 text-slate-800 font-sans antialiased">
<div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-brand-700 text-white flex flex-col fixed h-full">
        <div class="px-6 py-5 border-b border-white/10">
            <h1 class="text-lg font-bold tracking-tight">📁 Arsip Surat</h1>
            <p class="text-xs text-white/60">Sistem Kearsipan Digital</p>
        </div>
        <nav class="flex-1 px-3 py-4 space-y-1 text-sm">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-white/10 font-semibold' : 'hover:bg-white/5' }}">📊 Dashboard</a>
            <a href="{{ route('surat-masuk.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('surat-masuk.*') ? 'bg-white/10 font-semibold' : 'hover:bg-white/5' }}">📥 Surat Masuk</a>
            <a href="{{ route('surat-keluar.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('surat-keluar.*') ? 'bg-white/10 font-semibold' : 'hover:bg-white/5' }}">📤 Surat Keluar</a>
            <a href="{{ route('disposisi.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('disposisi.*') ? 'bg-white/10 font-semibold' : 'hover:bg-white/5' }}">🔀 Disposisi</a>
            <div class="pt-4 mt-4 border-t border-white/10 text-xs uppercase tracking-wider text-white/40 px-3">Master Data</div>
            @if(auth()->user()?->isAdmin())
            <a href="{{ route('kategori.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('kategori.*') ? 'bg-white/10 font-semibold' : 'hover:bg-white/5' }}">🏷️ Kategori Surat</a>
            <a href="{{ route('instansi.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('instansi.*') ? 'bg-white/10 font-semibold' : 'hover:bg-white/5' }}">🏢 Instansi</a>
            <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('users.*') ? 'bg-white/10 font-semibold' : 'hover:bg-white/5' }}">👥 Pengguna</a>
            @endif
        </nav>
        <div class="px-4 py-4 border-t border-white/10 text-xs text-white/50">
            {{-- Laravel 12 &copy; {{ date('Y') }} --}}
        </div>
    </aside>

    <!-- Main -->
    <div class="flex-1 ml-64 flex flex-col">
        <header class="bg-white shadow-sm sticky top-0 z-10">
            <div class="flex items-center justify-between px-8 py-4">
                <h2 class="text-xl font-semibold text-slate-700">@yield('title', 'Dashboard')</h2>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-400">{{ ucfirst(auth()->user()->role) }} &middot; {{ auth()->user()->jabatan }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-sm px-3 py-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100">Keluar</button>
                    </form>
                </div>
            </div>
        </header>

        <main class="p-8 flex-1">
            {{-- @if(session('success'))
                <div class="mb-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 text-sm">
                    ✅ {{ session('success') }}
                </div>
            @endif --}}
            @if($errors->any())
                <div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>
</body>
</html>
