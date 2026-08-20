@extends('layouts.app')

@section('title', 'Ubah Surat Masuk')

@section('content')
<div class="max-w-4xl mx-auto space-y-4 sm:space-y-6">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-800 tracking-tight">Ubah Surat Masuk</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Perbarui data arsip surat masuk dan informasi lokasi berkas fisik.</p>
        </div>
        <a href="{{ route('surat-masuk.index') }}" class="inline-flex items-center justify-center w-fit px-3.5 py-2 text-xs font-semibold text-slate-600 bg-white border border-slate-300 rounded-xl shadow-sm hover:bg-slate-50 hover:text-slate-800 focus:outline-none transition">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <!-- Menampilkan Alert Pesan Error jika Ada Validasi yang Gagal -->
    @if ($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl">
            <p class="text-xs font-bold text-rose-700 uppercase tracking-wider mb-1">Gagal Memperbarui Surat:</p>
            <ul class="list-disc list-inside text-xs text-rose-600 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('surat-masuk.update', $suratMasuk) }}" enctype="multipart/form-data" id="formEditSuratMasuk">
        @csrf 
        @method('PUT')

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
            
            <!-- Banner Nomor Agenda -->
            <div class="bg-blue-50/70 border-b border-blue-100 px-4 sm:px-6 py-3.5 flex items-center justify-between">
                <div class="flex items-center text-blue-900 text-xs sm:text-sm">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="font-medium">Nomor agenda: <strong class="font-semibold text-blue-700 font-mono bg-blue-100/80 px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-lg ml-1 text-xs border border-blue-200/60">#{{ $suratMasuk->nomor_agenda }}</strong></span>
                </div>
            </div>

            <div class="p-4 sm:p-6 space-y-5 sm:space-y-6">

                <!-- Section 1: Detail Utama Surat -->
                <div class="space-y-4">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2">Informasi Utama Surat</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
                        <!-- Nomor Surat -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Nomor Surat <span class="text-rose-500">*</span></label>
                            <input type="text" name="nomor_surat" value="{{ old('nomor_surat', $suratMasuk->nomor_surat) }}" required placeholder="Masukkan nomor surat"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500 outline-none transition duration-150 @error('nomor_surat') border-rose-500 bg-rose-50/30 @enderror">
                            @error('nomor_surat') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Instansi Pengirim -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Instansi Pengirim <span class="text-rose-500">*</span></label>
                            <select name="instansi_id" required class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-sm text-slate-700 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500 outline-none transition duration-150 font-medium @error('instansi_id') border-rose-500 bg-rose-50/30 @enderror">
                                @foreach($instansis as $i)
                                    <option value="{{ $i->id }}" @selected(old('instansi_id', $suratMasuk->instansi_id) == $i->id)>{{ $i->nama_instansi }}</option>
                                @endforeach
                            </select>
                            @error('instansi_id') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Tanggal Surat -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Tanggal Surat <span class="text-rose-500">*</span></label>
                            <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat', is_string($suratMasuk->tanggal_surat) ? $suratMasuk->tanggal_surat : optional($suratMasuk->tanggal_surat)->format('Y-m-d')) }}" required
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-sm text-slate-700 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500 outline-none transition duration-150 font-medium @error('tanggal_surat') border-rose-500 bg-rose-50/30 @enderror">
                            @error('tanggal_surat') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Tanggal Diterima -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Tanggal Diterima <span class="text-rose-500">*</span></label>
                            <input type="date" name="tanggal_terima" value="{{ old('tanggal_terima', is_string($suratMasuk->tanggal_terima) ? $suratMasuk->tanggal_terima : optional($suratMasuk->tanggal_terima)->format('Y-m-d')) }}" required
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-sm text-slate-700 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500 outline-none transition duration-150 font-medium @error('tanggal_terima') border-rose-500 bg-rose-50/30 @enderror">
                            @error('tanggal_terima') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Kategori Surat -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Kategori Surat <span class="text-rose-500">*</span></label>
                            <select name="kategori_surat_id" required class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-sm text-slate-700 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500 outline-none transition duration-150 font-medium @error('kategori_surat_id') border-rose-500 bg-rose-50/30 @enderror">
                                @foreach($kategoris as $k)
                                    <option value="{{ $k->id }}" @selected(old('kategori_surat_id', $suratMasuk->kategori_surat_id) == $k->id)>{{ $k->nama_kategori }}</option>
                                @endforeach
                            </select>
                            @error('kategori_surat_id') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Status <span class="text-rose-500">*</span></label>
                            <select name="status" required class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-sm text-slate-700 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500 outline-none transition duration-150 font-medium @error('status') border-rose-500 bg-rose-50/30 @enderror">
                                @foreach(['baru','diproses','didisposisikan','selesai','diarsipkan'] as $st)
                                    <option value="{{ $st }}" @selected(old('status', strtolower($suratMasuk->status ?? '')) == $st)>{{ ucfirst($st) }}</option>
                                @endforeach
                            </select>
                            @error('status') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Perihal -->
                    <div class="pt-1">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Perihal <span class="text-rose-500">*</span></label>
                        <textarea name="perihal" rows="3" required placeholder="Tuliskan perihal atau isi ringkas surat..."
                            class="w-full rounded-xl border border-slate-200 bg-slate-50/50 p-3.5 text-sm text-slate-700 placeholder-slate-400 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500 outline-none transition duration-150 @error('perihal') border-rose-500 bg-rose-50/30 @enderror">{{ old('perihal', $suratMasuk->perihal) }}</textarea>
                        @error('perihal') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Section 2: File Lampiran & Lokasi Fisik -->
                <div class="space-y-4 pt-2">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2">Lampiran & Arsip Fisik</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
                        <!-- File Upload & Preview -->
                        <div>
                            <div class="flex flex-wrap items-center justify-between gap-1 mb-1.5">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600">Ganti Scan/Lampiran <span class="text-slate-400 font-normal lowercase">(PDF / Gambar max 10MB)</span></label>
                                
                                @php
                                    $existingFile = $suratMasuk->lampiran ?? $suratMasuk->lampiran_file;
                                @endphp

                                @if($existingFile)
                                    <a href="{{ asset('storage/' . $existingFile) }}" target="_blank" class="inline-flex items-center text-[11px] font-semibold text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 border border-blue-200/80 px-2 py-0.5 rounded-lg transition">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Lihat Berkas saat ini
                                    </a>
                                @endif
                            </div>
                            
                            <!-- Input File Dual Mode (Laptop File Manager / HP Camera & Gallery) -->
                            <div class="relative">
                                <input type="file" name="lampiran" accept=".pdf,image/*" id="lampiranFile"
                                    class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-slate-200 rounded-xl cursor-pointer bg-slate-50/50">
                            </div>

                            <small class="text-slate-400 text-[11px] mt-1 block">Kosongkan jika tidak ingin mengganti file lampiran.</small>
                            @error('lampiran') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror

                            <!-- Container Pratinjau Berkas Baru -->
                            <div class="mt-3 p-3 bg-slate-50 border border-slate-200 rounded-xl" id="previewContainer" style="display: none;">
                                <p class="text-xs font-semibold text-slate-600 mb-1.5" id="previewTitle">Pratinjau Berkas Baru:</p>
                                
                                <!-- Preview jika berupa Gambar -->
                                <img id="imagePreview" src="#" alt="Preview" class="max-h-48 rounded-lg border border-slate-200 shadow-sm object-contain hidden">
                                
                                <!-- Preview jika berupa PDF -->
                                <div id="pdfInfo" class="hidden items-center text-xs text-slate-700 font-medium">
                                    <svg class="w-6 h-6 text-rose-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V7.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 1H7a2 2 0 00-2 2v16a2 2 0 002 2z"/></svg>
                                    <span id="pdfFileName" class="truncate">Dokumen PDF Terpilih</span>
                                </div>
                            </div>
                        </div>

                        <!-- Lokasi Fisik -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Lokasi Arsip Fisik</label>
                            <input type="text" name="lokasi_arsip_fisik" value="{{ old('lokasi_arsip_fisik', $suratMasuk->lokasi_arsip_fisik) }}" placeholder="Contoh: Rak A-3 Box 12"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500 outline-none transition duration-150 @error('lokasi_arsip_fisik') border-rose-500 bg-rose-50/30 @enderror">
                            @error('lokasi_arsip_fisik') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-2.5 px-4 sm:px-6 py-4 bg-slate-50 border-t border-slate-100">
                <a href="{{ route('surat-masuk.index') }}" class="w-full sm:w-auto text-center px-4 py-2.5 text-xs font-semibold text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-100 hover:text-slate-800 transition">
                    Batal
                </a>
                <button type="submit" id="btnSubmit" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 text-xs font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-md shadow-blue-600/30 transition">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    Perbarui Surat Masuk
                </button>
            </div>

        </div>
    </form>
</div>

<!-- Script Validasi Ukuran File, Multi-Format Preview & Prevent Double Submit -->
<script>
    document.getElementById('lampiranFile').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const previewContainer = document.getElementById('previewContainer');
        const imagePreview = document.getElementById('imagePreview');
        const pdfInfo = document.getElementById('pdfInfo');
        const pdfFileName = document.getElementById('pdfFileName');

        if (file) {
            // Batasan maksimal 10MB
            if (file.size > 10 * 1024 * 1024) {
                alert('Ukuran file/foto terlalu besar! Maksimal ukuran adalah 10MB.');
                this.value = '';
                previewContainer.style.display = 'none';
                return;
            }

            previewContainer.style.display = 'block';

            // Tampilkan preview berdasarkan jenis berkas
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                    pdfInfo.classList.add('hidden');
                    pdfInfo.classList.remove('flex');
                }
                reader.readAsDataURL(file);
            } else if (file.type === 'application/pdf') {
                imagePreview.classList.add('hidden');
                pdfFileName.textContent = file.name + ' (' + (file.size / (1024 * 1024)).toFixed(2) + ' MB)';
                pdfInfo.classList.remove('hidden');
                pdfInfo.classList.add('flex');
            } else {
                previewContainer.style.display = 'none';
            }
        } else {
            previewContainer.style.display = 'none';
        }
    });

    // Mencegah double submit saat form dikirim
    document.getElementById('formEditSuratMasuk').addEventListener('submit', function() {
        const btn = document.getElementById('btnSubmit');
        btn.disabled = true;
        btn.classList.add('opacity-75', 'cursor-not-allowed');
        btn.innerHTML = `<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memperbarui...`;
    });
</script>
@endsection