@extends('layouts.app')

@section('title', 'Ubah Surat Keluar')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">

    <!-- HEADER PAGE & TOMBOL KEMBALI -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('surat-keluar.index') }}" class="p-2 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-slate-900 shadow-sm transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Ubah Surat Keluar</h1>
                <p class="text-sm text-slate-500 mt-0.5">Perbarui informasi data dan berkas arsip surat keluar.</p>
            </div>
        </div>
    </div>

    <!-- FORM UTAMA -->
    <form method="POST" action="{{ route('surat-keluar.update', $suratKeluar) }}" enctype="multipart/form-data">
        @csrf 
        @method('PUT')

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
            
            <!-- Banner Info Nomor Surat -->
            <div class="bg-slate-50 border-b border-slate-200/80 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-2.5 text-slate-700 text-sm">
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    <span>Nomor Surat: <strong class="font-bold text-blue-600 font-mono bg-blue-50 px-2.5 py-1 rounded-lg border border-blue-200/80 ml-1">{{ $suratKeluar->nomor_surat }}</strong></span>
                </div>
            </div>

            <div class="p-6 space-y-6">

                <!-- SECTION 1: INFORMASI UTAMA SURAT -->
                <div class="space-y-4">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2">Informasi Utama Surat</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        
                        <!-- Tanggal Surat -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Tanggal Surat <span class="text-rose-500">*</span></label>
                            <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat', optional($suratKeluar->tanggal_surat)->format('Y-m-d')) }}" required
                                class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150 text-slate-700 font-medium @error('tanggal_surat') border-rose-500 @enderror">
                            @error('tanggal_surat') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Instansi Tujuan -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Instansi Tujuan <span class="text-rose-500">*</span></label>
                            <select name="instansi_id" required class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150 text-slate-700 font-medium @error('instansi_id') border-rose-500 @enderror">
                                <option value="">-- Pilih Instansi --</option>
                                @foreach($instansis as $i)
                                    <option value="{{ $i->id }}" @selected(old('instansi_id', $suratKeluar->instansi_id) == $i->id)>{{ $i->nama_instansi }}</option>
                                @endforeach
                            </select>
                            @error('instansi_id') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Kategori Surat -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Kategori Surat <span class="text-rose-500">*</span></label>
                            <select name="kategori_surat_id" required class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150 text-slate-700 font-medium @error('kategori_surat_id') border-rose-500 @enderror">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategoris as $k)
                                    <option value="{{ $k->id }}" @selected(old('kategori_surat_id', $suratKeluar->kategori_surat_id) == $k->id)>{{ $k->nama_kategori }}</option>
                                @endforeach
                            </select>
                            @error('kategori_surat_id') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Ditandatangani Oleh -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Ditandatangani Oleh</label>
                            <select name="ditandatangani_oleh" class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150 text-slate-700 font-medium @error('ditandatangani_oleh') border-rose-500 @enderror">
                                <option value="">- Belum Ditandatangani -</option>
                                @foreach($penandatangan as $p)
                                    <option value="{{ $p->id }}" @selected(old('ditandatangani_oleh', $suratKeluar->ditandatangani_oleh) == $p->id)>{{ $p->name }}</option>
                                @endforeach
                            </select>
                            @error('ditandatangani_oleh') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Status Surat -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Status Surat <span class="text-rose-500">*</span></label>
                            <select name="status" required class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150 text-slate-700 font-medium @error('status') border-rose-500 @enderror">
                                @foreach(['draf','diproses','disetujui','dikirim','diarsipkan'] as $st)
                                    <option value="{{ $st }}" @selected(old('status', $suratKeluar->status) == $st)>{{ ucfirst($st) }}</option>
                                @endforeach
                            </select>
                            @error('status') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Perihal -->
                    <div class="pt-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Perihal Surat <span class="text-rose-500">*</span></label>
                        <input type="text" name="perihal" value="{{ old('perihal', $suratKeluar->perihal) }}" required placeholder="Tuliskan perihal atau judul surat..."
                            class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150 text-slate-700 font-medium @error('perihal') border-rose-500 @enderror">
                        @error('perihal') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- SECTION 2: BERKAS DIGITAL (LAMPIRAN) -->
                <div class="space-y-4 pt-4 border-t border-slate-100">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2">Berkas Digital</h2>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600">
                                Ganti Berkas Surat <span class="text-slate-400 font-normal lowercase">(Kosongkan jika tidak diubah)</span>
                            </label>
                            
                            @if($suratKeluar->lampiran_file)
                                <a href="{{ route('lampiran.preview', $suratKeluar->lampiran_file) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-xl border border-blue-200/80 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <span>Lihat Berkas Saat Ini</span>
                                </a>
                            @endif
                        </div>
                        
                        <input type="file" name="lampiran_file" accept=".pdf"
                            class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-200 file:text-slate-700 hover:file:bg-slate-300 border border-slate-200 rounded-xl cursor-pointer bg-slate-50 transition">
                        <p class="text-[11px] text-slate-400 mt-1.5">Format berkas yang didukung: PDF (Maksimal 5MB)</p>
                        @error('lampiran_file') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

            </div>

            <!-- ACTION BUTTONS -->
            <div class="flex items-center justify-end gap-3 px-6 py-4 bg-slate-50 border-t border-slate-100">
                <a href="{{ route('surat-keluar.index') }}" class="px-4 py-2.5 text-xs font-semibold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-100 transition">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition shadow-md shadow-blue-600/30">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Simpan Perubahan</span>
                </button>
            </div>

        </div>
    </form>
</div>
@endsection