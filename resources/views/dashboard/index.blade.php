@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6 sm:space-y-8 pb-10">

    <!-- 1. WELCOME BANNER -->
    <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-indigo-600 to-indigo-800 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-blue-500/15">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="space-y-3">
                <div class="inline-flex items-center gap-2 bg-white/15 backdrop-blur-md px-3.5 py-1.5 rounded-full text-xs font-semibold text-blue-50 border border-white/25 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Sistem E-Arsip Aktif
                </div>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight">Selamat Datang, {{ auth()->user()->name ?? 'Administrator' }}!</h1>
                <p class="text-blue-100 text-xs sm:text-sm max-w-xl leading-relaxed">Kelola arsip surat masuk, surat keluar, dan disposisi dokumen dengan cepat, terstruktur, dan aman.</p>
            </div>
            
            <!-- Tombol Aksi Cepat: Responsif di HP & Desktop -->
            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <a href="{{ route('surat-masuk.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-blue-700 hover:bg-blue-50 font-bold rounded-xl text-xs sm:text-sm shadow-lg shadow-black/10 transition transform active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>Surat Masuk</span>
                </a>
                <a href="{{ route('surat-keluar.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/15 hover:bg-white/25 text-white border border-white/30 font-bold rounded-xl text-xs sm:text-sm backdrop-blur-md transition transform active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>Surat Keluar</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 2. STATS GRID: 1 kolom di HP, 2 di Tablet, 4 di Desktop -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Card 1: Surat Masuk -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Surat Masuk</span>
                <h3 class="text-2xl sm:text-3xl font-black text-slate-800 mt-1.5">{{ $totalSuratMasuk ?? 0 }}</h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
            </div>
        </div>

        <!-- Card 2: Surat Keluar -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Surat Keluar</span>
                <h3 class="text-2xl sm:text-3xl font-black text-slate-800 mt-1.5">{{ $totalSuratKeluar ?? 0 }}</h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
            </div>
        </div>

        <!-- Card 3: Belum Diproses -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Belum Diproses</span>
                <h3 class="text-2xl sm:text-3xl font-black text-slate-800 mt-1.5">{{ $suratPending ?? 0 }}</h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <!-- Card 4: Disposisi Saya -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Disposisi Saya</span>
                <h3 class="text-2xl sm:text-3xl font-black text-slate-800 mt-1.5">{{ $disposisiMenunggu ?? 0 }}</h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
            </div>
        </div>
    </div>

    <!-- 3. MAIN CONTENT: CHART & DISPOSISI -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Chart Section -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-sm">
            <h2 class="text-base font-bold text-slate-800 mb-4">Statistik 12 Bulan Terakhir</h2>
            <div class="relative h-72 w-full">
                <canvas id="suratChart"></canvas>
            </div>
        </div>

        <!-- Disposisi Widget -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-sm flex flex-col">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <h2 class="text-base font-bold text-slate-800">Disposisi Untuk Saya</h2>
                <a href="{{ route('disposisi.index') }}" class="text-xs font-bold text-blue-600 hover:underline">Lihat Semua</a>
            </div>
            <div class="mt-4 space-y-3 flex-1 overflow-y-auto max-h-64">
                @forelse($listDisposisi as $d)
                    <a href="{{ route('disposisi.show', $d->id) }}" class="block p-3.5 bg-slate-50 hover:bg-blue-50/60 rounded-xl transition border border-slate-100">
                        <p class="text-xs font-bold text-slate-800 truncate">{{ $d->suratMasuk->perihal ?? 'Surat' }}</p>
                        <p class="text-[11px] text-slate-500 mt-1">Dari: {{ $d->dari->name ?? '-' }}</p>
                    </a>
                @empty
                    <div class="flex flex-col items-center justify-center py-10 text-slate-400 text-center">
                        <svg class="w-10 h-10 mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                        <p class="text-xs font-medium">Tidak ada disposisi.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('suratChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [
                { 
                    label: 'Masuk', 
                    data: @json($chartDataMasuk), 
                    borderColor: '#2563eb', 
                    backgroundColor: 'rgba(37, 99, 235, 0.05)',
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.3 
                },
                { 
                    label: 'Keluar', 
                    data: @json($chartDataKeluar), 
                    borderColor: '#10b981', 
                    backgroundColor: 'rgba(16, 185, 129, 0.05)',
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.3 
                }
            ]
        },
        options: { 
            responsive: true, 
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true, 
                    min: 0,            
                    ticks: {
                        precision: 0   
                    }
                }
            }
        }
    });
</script>
@endsection