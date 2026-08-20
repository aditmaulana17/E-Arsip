@extends('layouts.app')

@section('title', 'Detail Surat Masuk - ' . $suratMasuk->nomor_agenda)

@section('content')
<div class="space-y-6">

    <!-- Header Action Bar -->
    <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-sm border border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('surat-masuk.index') }}" class="p-2.5 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-all duration-200 border border-slate-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl font-bold text-slate-800 tracking-tight">Detail Surat Masuk</h1>
                    <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                        #{{ $suratMasuk->nomor_agenda }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Peninjauan detail informasi serta pratinjau berkas digital</p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-2.5">
            <a href="{{ route('surat-masuk.edit', $suratMasuk->id) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-500 text-white font-semibold text-xs sm:text-sm hover:bg-amber-600 shadow-md shadow-amber-500/20 transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <span>Edit Surat</span>
            </a>

            @if(Route::has('surat-masuk.cetak-disposisi'))
                <a href="{{ route('surat-masuk.cetak-disposisi', $suratMasuk->id) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-rose-600 text-white font-semibold text-xs sm:text-sm hover:bg-rose-700 shadow-md shadow-rose-600/20 transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Cetak Disposisi</span>
                </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Kiri: Metadata Surat -->
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-2">
                        <div class="p-2 rounded-xl bg-blue-50 text-blue-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h2 class="font-bold text-slate-800 text-sm">Informasi Surat</h2>
                    </div>
                </div>

                <div class="p-5 space-y-4 text-xs sm:text-sm">
                    <div>
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Perihal</span>
                        <p class="font-bold text-slate-800 text-base leading-snug">{{ $suratMasuk->perihal }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 pt-2 border-t border-slate-100">
                        <div>
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Nomor Agenda</span>
                            <span class="inline-block font-semibold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-lg border border-blue-100">
                                {{ $suratMasuk->nomor_agenda }}
                            </span>
                        </div>
                        <div>
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Kategori</span>
                            <span class="inline-block font-semibold text-slate-700 bg-slate-100 px-2.5 py-1 rounded-lg">
                                {{ $suratMasuk->kategori->nama_kategori ?? 'Umum' }}
                            </span>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-slate-100">
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Nomor Surat</span>
                        <p class="font-semibold text-slate-800">{{ $suratMasuk->nomor_surat ?? '-' }}</p>
                    </div>

                    <div class="pt-2 border-t border-slate-100">
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Asal Instansi</span>
                        <div class="flex items-center gap-2 p-2.5 bg-slate-50 rounded-xl border border-slate-100">
                            <svg class="w-4 h-4 text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <span class="font-semibold text-slate-800">{{ $suratMasuk->instansi->nama_instansi ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 pt-2 border-t border-slate-100">
                        <div>
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Tanggal Surat</span>
                            <p class="font-medium text-slate-700">
                                {{ $suratMasuk->tanggal_surat ? \Carbon\Carbon::parse($suratMasuk->tanggal_surat)->translatedFormat('d M Y') : '-' }}
                            </p>
                        </div>
                        <div>
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Tanggal Terima</span>
                            <p class="font-medium text-emerald-600">
                                {{ $suratMasuk->tanggal_terima ? \Carbon\Carbon::parse($suratMasuk->tanggal_terima)->translatedFormat('d M Y') : '-' }}
                            </p>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-slate-100">
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Penerima</span>
                        <p class="font-medium text-slate-700">{{ $suratMasuk->penerima->name ?? 'Administrator' }}</p>
                    </div>

                    <div class="pt-2 border-t border-slate-100">
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Ringkasan / Catatan</span>
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 text-slate-600 text-xs leading-relaxed">
                            {{ $suratMasuk->ringkasan ?? 'Tidak ada ringkasan catatan.' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kanan: Preview Berkas Digital -->
        <div class="lg:col-span-7">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden flex flex-col h-full min-h-[620px]">
                
                @php
                    $filePath = $suratMasuk->lampiran_file ?? $suratMasuk->lampiran;
                    $fileExists = $filePath && \Illuminate\Support\Facades\Storage::disk('public')->exists($filePath);
                @endphp

                <!-- Card Header Lampiran -->
                <div class="px-5 py-3.5 border-b border-slate-100 flex flex-wrap items-center justify-between gap-3 bg-slate-50/50">
                    <div class="flex items-center gap-2.5">
                        <div class="p-2 rounded-xl bg-rose-50 text-rose-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <h2 class="font-bold text-slate-800 text-sm">Berkas Lampiran (Digital)</h2>
                            <p class="text-[10px] text-slate-400 font-medium">Pratinjau dokumen langsung di sistem</p>
                        </div>
                    </div>

                    @if ($fileExists && Route::has('surat-masuk.preview'))
                        <div class="flex items-center gap-2">
                            <a href="{{ route('surat-masuk.preview', $suratMasuk->id) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200 text-xs font-semibold transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                <span>Buka Tab Baru</span>
                            </a>
                            @if(Route::has('surat-masuk.download'))
                                <a href="{{ route('surat-masuk.download', $suratMasuk->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-xs font-semibold shadow-sm transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    <span>Unduh Berkas</span>
                                </a>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Body Document Viewer -->
                <div class="flex-1 bg-slate-900/5 relative flex items-center justify-center min-h-[550px]">
                    @if ($fileExists && Route::has('surat-masuk.preview'))
                        @php
                            $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                        @endphp

                        @if ($extension === 'pdf')
                            <iframe 
                                src="{{ route('surat-masuk.preview', $suratMasuk->id) }}" 
                                class="w-full h-full min-h-[550px] border-0"
                                title="Pratinjau PDF">
                            </iframe>
                        @elseif (in_array($extension, ['jpg', 'jpeg', 'png', 'webp']))
                            <div class="p-4 text-center">
                                <img src="{{ route('surat-masuk.preview', $suratMasuk->id) }}" 
                                     alt="Lampiran Surat" 
                                     class="max-h-[520px] mx-auto rounded-xl shadow-lg border border-white object-contain">
                            </div>
                        @else
                            <div class="text-center p-6 my-auto">
                                <div class="w-16 h-16 bg-amber-50 rounded-2xl flex items-center justify-center mx-auto text-amber-500 mb-3 border border-amber-100">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <h3 class="text-slate-800 font-bold text-sm">Format Berkas (<code>.{{ $extension }}</code>)</h3>
                                <p class="text-slate-500 text-xs mt-1 mb-4">Berkas ini tidak dapat ditayangkan langsung di halaman web.</p>
                                @if(Route::has('surat-masuk.download'))
                                    <a href="{{ route('surat-masuk.download', $suratMasuk->id) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 text-white font-semibold text-xs hover:bg-blue-700 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 013 3h10a3 3 0 013-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        <span>Unduh Dokumen Sekarang</span>
                                    </a>
                                @endif
                            </div>
                        @endif
                    @else
                        <div class="text-center p-8 my-auto">
                            <div class="w-16 h-16 bg-rose-50 rounded-2xl flex items-center justify-center mx-auto text-rose-500 mb-3 border border-rose-100">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <h3 class="text-slate-800 font-bold text-sm">Berkas Digital Tidak Ada</h3>
                            <p class="text-slate-400 text-xs mt-1 max-w-xs mx-auto mb-4">
                                Berkas lampiran belum diunggah atau tidak ditemukan di penyimpanan server.
                            </p>
                            <a href="{{ route('surat-masuk.edit', $suratMasuk->id) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-amber-500 text-white font-semibold text-xs hover:bg-amber-600 shadow-sm transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                <span>Unggah Berkas Melalui Edit</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection