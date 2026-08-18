@extends('layouts.app')
@section('title', 'Buat Surat Keluar')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header Page -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Buat Surat Keluar</h1>
            <p class="text-sm text-slate-500">Isi formulir di bawah ini untuk membuat dan mencatat arsip surat keluar baru.</p>
        </div>
        <a href="{{ route('surat-keluar.index') }}" class="inline-flex items-center px-3.5 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-lg shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <form method="POST" action="{{ route('surat-keluar.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="bg-white rounded-xl shadow-sm border border-slate-200/80 overflow-hidden">
            
            <!-- Banner Format Nomor Surat Otomatis -->
            <div class="bg-blue-50/70 border-b border-blue-100 px-6 py-3.5 flex items-center justify-between">
                <div class="flex items-center text-blue-900 text-sm">
                    <svg class="w-5 h-5 text-blue-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Nomor surat akan digenerate otomatis sesuai format: <strong class="font-semibold text-blue-800 ml-1 font-mono bg-blue-100 px-2 py-0.5 rounded">001/KODE-KATEGORI/BULAN-ROMAWI/TAHUN</strong></span>
                </div>
            </div>

            <div class="p-6 space-y-6">

                <!-- Section 1: Informasi Utama Surat -->
                <div class="space-y-4">
                    <h2 class="text-xs font-semibold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2">Informasi Utama Surat</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Tanggal Surat -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Surat <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat', date('Y-m-d')) }}" required
                                class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-brand-500 focus:ring-brand-500 py-2 px-3 border @error('tanggal_surat') border-red-500 @enderror">
                            @error('tanggal_surat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Instansi Tujuan -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Instansi Tujuan <span class="text-red-500">*</span></label>
                            <select name="instansi_id" required class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-brand-500 focus:ring-brand-500 py-2 px-3 border @error('instansi_id') border-red-500 @enderror">
                                <option value="" disabled selected>Pilih instansi</option>
                                @foreach($instansis as $i)
                                    <option value="{{ $i->id }}" {{ old('instansi_id') == $i->id ? 'selected' : '' }}>{{ $i->nama_instansi }}</option>
                                @endforeach
                            </select>
                            @error('instansi_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Kategori Surat -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Kategori Surat <span class="text-red-500">*</span></label>
                            <select name="kategori_surat_id" required class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-brand-500 focus:ring-brand-500 py-2 px-3 border @error('kategori_surat_id') border-red-500 @enderror">
                                <option value="" disabled selected>Pilih kategori</option>
                                @foreach($kategoris as $k)
                                    <option value="{{ $k->id }}" {{ old('kategori_surat_id') == $k->id ? 'selected' : '' }}>
                                        {{ $k->nama_kategori }} ({{ $k->kode }})
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori_surat_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Ditandatangani Oleh -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Ditandatangani Oleh</label>
                            <select name="ditandatangani_oleh" class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-brand-500 focus:ring-brand-500 py-2 px-3 border @error('ditandatangani_oleh') border-red-500 @enderror">
                                <option value="">- Pilih Penandatangan -</option>
                                @foreach($penandatangan as $p)
                                    <option value="{{ $p->id }}" {{ old('ditandatangani_oleh') == $p->id ? 'selected' : '' }}>
                                        {{ $p->name }} ({{ $p->jabatan }})
                                    </option>
                                @endforeach
                            </select>
                            @error('ditandatangani_oleh') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Status (Full width di mobile, setengah grid di desktop) -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Status <span class="text-red-500">*</span></label>
                            <select name="status" required class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-brand-500 focus:ring-brand-500 py-2 px-3 border @error('status') border-red-500 @enderror">
                                <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="dikirim" {{ old('status') == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                            </select>
                            @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Perihal -->
                    <div class="pt-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Perihal <span class="text-red-500">*</span></label>
                        <textarea name="perihal" rows="3" required placeholder="Tuliskan perihal atau ringkasan isi surat keluar..."
                            class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-brand-500 focus:ring-brand-500 p-3 border @error('perihal') border-red-500 @enderror">{{ old('perihal') }}</textarea>
                        @error('perihal') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Section 2: Upload File -->
                <div class="space-y-4 pt-2">
                    <h2 class="text-xs font-semibold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2">Berkas Digital</h2>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Upload File Surat <span class="text-slate-400 font-normal">(PDF max 10MB)</span></label>
                        <input type="file" name="lampiran_file" accept=".pdf"
                            class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 border border-slate-300 rounded-lg cursor-pointer bg-slate-50/50">
                        @error('lampiran_file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 px-6 py-4 bg-slate-50 border-t border-slate-100">
                <a href="{{ route('surat-keluar.index') }}" class="px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-lg hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-slate-300 transition">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-brand-600 rounded-lg hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 shadow-sm transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Surat Keluar
                </button>
            </div>

        </div>
    </form>
</div>
@endsection