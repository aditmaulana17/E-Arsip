@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6 sm:space-y-8">

    <!-- WELCOME BANNER -->
    <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl sm:rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-blue-500/10">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md px-3 py-1 rounded-full text-[10px] sm:text-xs font-medium text-blue-100 border border-white/20">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Sistem E-Arsip Aktif
                </div>
                <h1 class="text-xl sm:text-3xl font-bold tracking-tight">Selamat Datang, {{ auth()->user()->name ?? 'Administrator' }}!</h1>
                <p class="text-blue-100/90 text-xs sm:text-sm max-w-xl">Kelola arsip surat masuk, surat keluar, dan disposisi dokumen dengan cepat, terstruktur, dan aman.</p>
            </div>
            
            <!-- Tombol jadi flex-col di HP, flex-row di Desktop -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3 shrink-0">
                <a href="{{ route('surat-masuk.create') }}" class="inline-flex justify-center items-center gap-2 px-4 py-2.5 bg-white text-blue-700 hover:bg-blue-50 rounded-xl text-xs font-semibold transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Surat Masuk</span>
                </a>
                <a href="{{ route('surat-keluar.create') }}" class="inline-flex justify-center items-center gap-2 px-4 py-2.5 bg-blue-500/30 hover:bg-blue-500/40 text-white border border-white/20 rounded-xl text-xs font-semibold backdrop-blur-md transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Surat Keluar</span>
                </a>
            </div>
        </div>
    </div>

    <!-- STATS GRID: 1 kolom di HP, 2 di Tablet, 4 di Desktop -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @php
            $stats = [
                ['title' => 'Total Surat Masuk', 'val' => $totalSuratMasuk, 'icon' => 'M20 13V6a2', 'color' => 'blue'],
                ['title' => 'Total Surat Keluar', 'val' => $totalSuratKeluar, 'icon' => 'M12 19l9 2', 'color' => 'emerald'],
                ['title' => 'Belum Diproses', 'val' => $suratPending, 'icon' => 'M12 8v4l3', 'color' => 'amber'],
                ['title' => 'Disposisi Saya', 'val' => $disposisiMenunggu, 'icon' => 'M8 7h12m', 'color' => 'purple'],
            ];
        @endphp
        
        @foreach($stats as $s)
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">{{ $s['title'] }}</span>
            </div>
            <div class="mt-3 flex items-baseline justify-between">
                <span class="text-2xl font-extrabold text-slate-800">{{ $s['val'] }}</span>
            </div>
        </div>
        @endforeach
    </div>

    <!-- MAIN CONTENT: CHART & DISPOSISI -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Chart Section -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm">
            <h2 class="text-sm font-bold text-slate-800 mb-4">Statistik 12 Bulan Terakhir</h2>
            <div class="relative h-64 w-full">
                <canvas id="suratChart"></canvas>
            </div>
        </div>

        <!-- Disposisi Widget -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <h2 class="text-sm font-bold text-slate-800">Disposisi Untuk Saya</h2>
                <a href="{{ route('disposisi.index') }}" class="text-xs font-semibold text-blue-600">Lihat Semua</a>
            </div>
            <div class="mt-4 space-y-3">
                @forelse($listDisposisi as $d)
                    <a href="{{ route('disposisi.show', $d->id) }}" class="block p-3 bg-slate-50 hover:bg-blue-50 rounded-xl transition">
                        <p class="text-xs font-bold text-slate-700 truncate">{{ $d->suratMasuk->perihal ?? 'Surat' }}</p>
                        <p class="text-[10px] text-slate-500">Dari: {{ $d->dari->name ?? '-' }}</p>
                    </a>
                @empty
                    <p class="text-xs text-center text-slate-400 py-6">Tidak ada disposisi.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Pastikan Chart.js tetap responsive
    const ctx = document.getElementById('suratChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [
                { label: 'Masuk', data: @json($chartDataMasuk), borderColor: '#2563eb', tension: 0.4 },
                { label: 'Keluar', data: @json($chartDataKeluar), borderColor: '#10b981', tension: 0.4 }
            ]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });
</script>
@endsection