@extends('layouts.app')

@section('title', 'Detail Surat Masuk')

@section('content')
<div class="space-y-4 sm:space-y-6">

    <!-- HEADER PAGE & TOMBOL AKSI -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('surat-masuk.index') }}" class="p-2 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-slate-900 shadow-sm transition shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-800 tracking-tight">Detail Surat Masuk</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Informasi arsip lengkap dan riwayat instruksi disposisi.</p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <!-- Edit Surat -->
            <a href="{{ route('surat-masuk.edit', $suratMasuk) }}" class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 text-xs font-semibold bg-amber-50 text-amber-700 hover:bg-amber-100 rounded-xl transition border border-amber-200 shadow-sm grow sm:grow-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <span>Edit Surat</span>
            </a>

            <!-- Cetak Lembar Disposisi -->
            <a href="{{ route('surat-masuk.cetak-disposisi', $suratMasuk) }}" target="_blank" class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 text-xs font-semibold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 rounded-xl transition border border-emerald-200 shadow-sm grow sm:grow-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak Disposisi</span>
            </a>

            <!-- Cetak Label -->
            <a href="{{ route('surat-masuk.label', $suratMasuk) }}" target="_blank" class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 text-xs font-semibold bg-white text-slate-700 hover:bg-slate-50 rounded-xl transition border border-slate-300 shadow-sm grow sm:grow-0">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 11h10M7 15h10M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"/></svg>
                <span>Cetak Label</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- MAIN PANEL: INFORMASI UTAMA SURAT MASUK -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200/80 p-4 sm:p-6 space-y-6">
            
            <!-- Perihal & Badge Agenda -->
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start pb-4 border-b border-slate-100 gap-3">
                <div class="space-y-1">
                    <h3 class="text-base sm:text-lg font-bold text-slate-800 leading-snug break-words">{{ $suratMasuk->perihal }}</h3>
                    <div class="text-xs text-slate-500 flex flex-wrap items-center gap-2 pt-1">
                        <span>Agenda: <strong class="font-mono text-blue-600 bg-blue-50 px-2 py-0.5 rounded border border-blue-100">#{{ $suratMasuk->nomor_agenda }}</strong></span>
                        <span class="hidden sm:inline">&middot;</span>
                        <span>No. Surat: <strong class="text-slate-700 font-semibold">{{ $suratMasuk->nomor_surat }}</strong></span>
                    </div>
                </div>

                @php
                    $badgeClass = match(strtolower($suratMasuk->status ?? '')) {
                        'baru'          => 'bg-blue-50 text-blue-700 border-blue-200',
                        'diproses'      => 'bg-amber-50 text-amber-700 border-amber-200',
                        'didisposisikan'=> 'bg-purple-50 text-purple-700 border-purple-200',
                        'selesai'       => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        'diarsipkan'    => 'bg-slate-100 text-slate-700 border-slate-200',
                        default         => 'bg-slate-100 text-slate-600 border-slate-200'
                    };
                @endphp
                <span class="self-start inline-block px-3 py-1 rounded-full text-xs font-semibold border shrink-0 {{ $badgeClass }}">
                    {{ ucfirst($suratMasuk->status ?? 'Baru') }}
                </span>
            </div>

            <!-- Detail Grid Metadata -->
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5 text-xs">
                <div>
                    <dt class="font-bold uppercase tracking-wider text-slate-400 mb-1">Instansi Pengirim</dt>
                    <dd class="text-xs sm:text-sm font-semibold text-slate-700 bg-slate-50 px-3.5 py-2.5 rounded-xl border border-slate-100 break-words">
                        {{ $suratMasuk->instansi->nama_instansi ?? '-' }}
                    </dd>
                </div>
                <div>
                    <dt class="font-bold uppercase tracking-wider text-slate-400 mb-1">Kategori Surat</dt>
                    <dd class="text-xs sm:text-sm font-semibold text-slate-700 bg-slate-50 px-3.5 py-2.5 rounded-xl border border-slate-100 break-words">
                        {{ $suratMasuk->kategori->nama_kategori ?? '-' }}
                    </dd>
                </div>
                <div>
                    <dt class="font-bold uppercase tracking-wider text-slate-400 mb-1">Tanggal Surat</dt>
                    <dd class="text-xs sm:text-sm font-semibold text-slate-700 bg-slate-50 px-3.5 py-2.5 rounded-xl border border-slate-100">
                        {{ optional($suratMasuk->tanggal_surat)->format('d-m-Y') ?? '-' }}
                    </dd>
                </div>
                <div>
                    <dt class="font-bold uppercase tracking-wider text-slate-400 mb-1">Tanggal Diterima</dt>
                    <dd class="text-xs sm:text-sm font-semibold text-slate-700 bg-slate-50 px-3.5 py-2.5 rounded-xl border border-slate-100">
                        {{ optional($suratMasuk->tanggal_terima)->format('d-m-Y') ?? '-' }}
                    </dd>
                </div>
                <div>
                    <dt class="font-bold uppercase tracking-wider text-slate-400 mb-1">Diterima Oleh</dt>
                    <dd class="text-xs sm:text-sm font-semibold text-slate-700 bg-slate-50 px-3.5 py-2.5 rounded-xl border border-slate-100 break-words">
                        {{ $suratMasuk->penerima->name ?? '-' }}
                    </dd>
                </div>
                <div>
                    <dt class="font-bold uppercase tracking-wider text-slate-400 mb-1">Lokasi Arsip Fisik</dt>
                    <dd class="text-xs sm:text-sm font-semibold text-slate-700 bg-slate-50 px-3.5 py-2.5 rounded-xl border border-slate-100 break-words">
                        {{ $suratMasuk->lokasi_arsip_fisik ?? '-' }}
                    </dd>
                </div>
            </dl>

            <!-- Lampiran Berkas Digital & Preview -->
            <div class="pt-4 border-t border-slate-100 space-y-3">
                <dt class="text-xs font-bold uppercase tracking-wider text-slate-400">Berkas Lampiran (Digital)</dt>
                @if($suratMasuk->lampiran_file)
                    @php
                        $extension = pathinfo($suratMasuk->lampiran_file, PATHINFO_EXTENSION);
                        $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'webp']);
                        $fileUrl = route('lampiran.preview', $suratMasuk->lampiran_file);
                    @endphp

                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ $fileUrl }}" target="_blank" class="inline-flex items-center gap-2 text-xs font-semibold bg-blue-50 text-blue-600 px-4 py-2.5 rounded-xl border border-blue-200/80 hover:bg-blue-100 transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <span>Buka Lampiran Sepenuhnya ({{ strtoupper($extension) }})</span>
                        </a>
                    </div>

                    <!-- Embedded Preview jika format Gambar atau PDF -->
                    <div class="mt-3 rounded-xl border border-slate-200 overflow-hidden bg-slate-50 p-2">
                        @if($isImage)
                            <img src="{{ $fileUrl }}" alt="Lampiran Surat" class="max-h-96 w-auto mx-auto rounded-lg object-contain">
                        @elseif(strtolower($extension) === 'pdf')
                            <iframe src="{{ $fileUrl }}" class="w-full h-80 rounded-lg border-0"></iframe>
                        @endif
                    </div>
                @else
                    <p class="text-xs text-slate-400 italic bg-slate-50 p-3 rounded-xl border border-slate-100">Tidak ada lampiran berkas digital.</p>
                @endif
            </div>

        </div>

        <!-- SIDE PANEL: RIWAYAT DISPOSISI -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-4 sm:p-6 h-fit">
            <div class="flex justify-between items-center mb-4 pb-3 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-sm">Riwayat Disposisi</h3>
                <a href="{{ route('disposisi.create', $suratMasuk) }}" class="text-xs px-3 py-1.5 rounded-xl bg-purple-50 text-purple-700 border border-purple-200 hover:bg-purple-100 transition font-semibold flex items-center gap-1 shrink-0">
                    <span>+ Disposisi</span>
                </a>
            </div>
            
            <div class="space-y-3">
                @forelse($suratMasuk->disposisi as $d)
                    <div class="border-l-4 border-purple-500 bg-slate-50/80 p-3.5 rounded-r-2xl border border-slate-100 space-y-2">
                        <p class="text-xs font-bold text-slate-800 leading-snug">
                            {{ $d->dari->name ?? 'Admin' }} <span class="text-purple-600 font-normal mx-0.5">→</span> {{ $d->kepada->name ?? '-' }}
                        </p>
                        <p class="text-xs text-slate-600 leading-relaxed break-words">{{ $d->instruksi ?? '-' }}</p>
                        <div class="flex items-center justify-between pt-2 border-t border-slate-200/60">
                            <span class="text-[10px] px-2 py-0.5 rounded-full bg-purple-100 text-purple-700 font-semibold">
                                {{ ucfirst($d->status ?? 'Dikirim') }}
                            </span>
                            <span class="text-[10px] text-slate-400 font-medium">
                                {{ optional($d->created_at)->format('d/m/Y H:i') }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <div class="w-10 h-10 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400 mx-auto mb-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                        </div>
                        <p class="text-xs text-slate-500 font-medium">Belum ada riwayat disposisi.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection