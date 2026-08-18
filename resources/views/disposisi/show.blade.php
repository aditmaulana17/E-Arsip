@extends('layouts.app')

@section('title', 'Detail Disposisi')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">

    <!-- HEADER & BARIS TOMBOL AKSI -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Detail Disposisi</h1>
            <p class="text-sm text-slate-500 mt-0.5">Informasi lengkap instruksi disposisi surat masuk.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('disposisi.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Kembali</span>
            </a>
            <a href="{{ route('disposisi.edit', $disposisi) }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-semibold transition inline-flex items-center gap-2 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <span>Ubah</span>
            </a>
        </div>
    </div>

    <!-- KARTU DETAIL DISPOSISI -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 space-y-6">
        
        <!-- Informasi Status & Sifat -->
        <div class="flex flex-wrap items-center justify-between gap-4 p-4 bg-slate-50 rounded-xl border border-slate-100">
            <div>
                <span class="text-xs text-slate-400 block font-medium uppercase tracking-wider">Status Disposisi</span>
                <span class="inline-block mt-1 px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                    {{ ucfirst($disposisi->status ?? 'menunggu') }}
                </span>
            </div>
            <div>
                <span class="text-xs text-slate-400 block font-medium uppercase tracking-wider">Sifat Surat</span>
                @php
                    $sifatClass = match(strtolower($disposisi->sifat ?? '')) {
                        'segera'  => 'bg-rose-50 text-rose-700 border-rose-200',
                        'penting' => 'bg-amber-50 text-amber-700 border-amber-200',
                        'rahasia' => 'bg-purple-50 text-purple-700 border-purple-200',
                        'biasa'   => 'bg-blue-50 text-blue-700 border-blue-200',
                        default   => 'bg-slate-100 text-slate-600 border-slate-200'
                    };
                @endphp
                <span class="inline-block mt-1 px-3 py-1 rounded-full text-xs font-semibold border {{ $sifatClass }}">
                    {{ ucfirst($disposisi->sifat ?? 'Biasa') }}
                </span>
            </div>
            <div>
                <span class="text-xs text-slate-400 block font-medium uppercase tracking-wider">Batas Waktu</span>
                <span class="text-sm font-semibold text-slate-700 block mt-1">
                    {{ optional($disposisi->batas_waktu)->format('d F Y') ?? ($disposisi->batas_waktu ? \Carbon\Carbon::parse($disposisi->batas_waktu)->format('d F Y') : '-') }}
                </span>
            </div>
        </div>

        <!-- Rincian Data Disposisi -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Surat Masuk Asal</label>
                <div class="mt-1">
                    @if($disposisi->suratMasuk)
                        <a href="{{ route('surat-masuk.show', $disposisi->suratMasuk) }}" class="text-blue-600 hover:underline font-bold text-base inline-flex items-center gap-1.5">
                            <span>#{{ $disposisi->suratMasuk->nomor_agenda }}</span>
                            <span class="text-sm text-slate-500 font-normal">({{ $disposisi->suratMasuk->nomor_surat ?? '-' }})</span>
                        </a>
                    @else
                        <span class="text-slate-500">-</span>
                    @endif
                </div>
            </div>

            <div>
                <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Tanggal Disposisi</label>
                <p class="mt-1 text-sm font-semibold text-slate-700">
                    {{ optional($disposisi->tanggal_disposisi)->format('d F Y') ?? ($disposisi->tanggal_disposisi ? \Carbon\Carbon::parse($disposisi->tanggal_disposisi)->format('d F Y') : '-') }}
                </p>
            </div>

            <div>
                <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Pengirim (Dari)</label>
                <p class="mt-1 text-sm font-semibold text-slate-700">
                    {{ $disposisi->dari->name ?? '-' }}
                </p>
            </div>

            <div>
                <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Penerima / Tujuan</label>
                <p class="mt-1 text-sm font-semibold text-slate-700">
                    {{ $disposisi->kepada->name ?? $disposisi->tujuan ?? $disposisi->penerima ?? '-' }}
                </p>
            </div>
        </div>

        <hr class="border-slate-100">

        <!-- Isi Instruksi -->
        <div>
            <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Isi Instruksi / Catatan</label>
            <div class="mt-2 p-4 bg-slate-50 rounded-xl border border-slate-200/60 text-slate-700 text-sm leading-relaxed whitespace-pre-line">
                {{ $disposisi->isi_disposisi ?? $disposisi->catatan ?? 'Tidak ada catatan instruksi.' }}
            </div>
        </div>

    </div>

</div>
@endsection