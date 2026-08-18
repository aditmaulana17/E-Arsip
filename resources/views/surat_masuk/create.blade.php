@extends('layouts.app')

@section('title', 'Catat Surat Masuk')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header Page -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Catat Surat Masuk</h1>
            <p class="text-sm text-slate-500 mt-0.5">Isi formulir di bawah ini untuk menambahkan data arsip surat masuk baru.</p>
        </div>
        <a href="{{ route('surat-masuk.index') }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold text-slate-600 bg-white border border-slate-300 rounded-xl shadow-sm hover:bg-slate-50 hover:text-slate-800 focus:outline-none transition">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <form method="POST" action="{{ route('surat-masuk.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
            
            <!-- Banner Nomor Agenda Otomatis -->
            <div class="bg-blue-50/70 border-b border-blue-100 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center text-blue-900 text-sm">
                    <svg class="w-5 h-5 text-blue-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="font-medium">Nomor agenda otomatis: <strong class="font-semibold text-blue-700 font-mono bg-blue-100/80 px-2.5 py-1 rounded-lg ml-1 text-xs border border-blue-200/60">{{ $nomorAgenda }}</strong></span>
                </div>
            </div>

            <div class="p-6 space-y-6">

                <!-- Section 1: Detail Surat -->
                <div class="space-y-4">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2">Informasi Utama Surat</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Nomor Surat -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Nomor Surat <span class="text-rose-500">*</span></label>
                            <input type="text" name="nomor_surat" value="{{ old('nomor_surat') }}" required placeholder="Masukkan nomor surat"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500 outline-none transition duration-150 @error('nomor_surat') border-rose-500 bg-rose-50/30 @enderror">
                            @error('nomor_surat') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Instansi Pengirim -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Instansi Pengirim <span class="text-rose-500">*</span></label>
                            <select name="instansi_id" required class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-sm text-slate-700 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500 outline-none transition duration-150 font-medium">
                                <option value="" disabled selected>Pilih instansi</option>
                                @foreach($instansis as $i)
                                    <option value="{{ $i->id }}" {{ old('instansi_id') == $i->id ? 'selected' : '' }}>{{ $i->nama_instansi }}</option>
                                @endforeach
                            </select>
                            @error('instansi_id') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Tanggal Surat -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Tanggal Surat <span class="text-rose-500">*</span></label>
                            <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat') }}" required
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-sm text-slate-700 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500 outline-none transition duration-150 font-medium">
                            @error('tanggal_surat') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Tanggal Diterima -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Tanggal Diterima <span class="text-rose-500">*</span></label>
                            <input type="date" name="tanggal_terima" value="{{ old('tanggal_terima', date('Y-m-d')) }}" required
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-sm text-slate-700 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500 outline-none transition duration-150 font-medium">
                            @error('tanggal_terima') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Kategori Surat -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Kategori Surat <span class="text-rose-500">*</span></label>
                            <select name="kategori_surat_id" required class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-sm text-slate-700 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500 outline-none transition duration-150 font-medium">
                                <option value="" disabled selected>Pilih kategori</option>
                                @foreach($kategoris as $k)
                                    <option value="{{ $k->id }}" {{ old('kategori_surat_id') == $k->id ? 'selected' : '' }}>
                                        {{ $k->nama_kategori }} @if(isset($k->sifat))({{ ucfirst($k->sifat) }})@endif
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori_surat_id') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Status <span class="text-rose-500">*</span></label>
                            <select name="status" required class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-sm text-slate-700 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500 outline-none transition duration-150 font-medium">
                                <option value="baru" {{ old('status') == 'baru' ? 'selected' : '' }}>Baru</option>
                                <option value="diproses" {{ old('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                <option value="diarsipkan" {{ old('status') == 'diarsipkan' ? 'selected' : '' }}>Diarsipkan</option>
                            </select>
                            @error('status') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Perihal -->
                    <div class="pt-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Perihal <span class="text-rose-500">*</span></label>
                        <textarea name="perihal" rows="3" required placeholder="Tuliskan perihal atau isi ringkas surat..."
                            class="w-full rounded-xl border border-slate-200 bg-slate-50/50 p-3.5 text-sm text-slate-700 placeholder-slate-400 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500 outline-none transition duration-150 @error('perihal') border-rose-500 bg-rose-50/30 @enderror">{{ old('perihal') }}</textarea>
                        @error('perihal') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Section 2: Lampiran & Lokasi Fisik -->
                <div class="space-y-4 pt-2">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2">Lampiran & Arsip Fisik</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Custom File Input / Scan Kamera -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Scan / Lampiran <span class="text-slate-400 font-normal lowercase">(PDF / Gambar max 10MB)</span></label>
                            
                            <!-- Input File dengan dukungan tangkapan kamera langsung -->
                            <input type="file" name="lampiran_file" accept=".pdf,image/*" capture="environment" id="lampiranFile"
                                class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 border border-slate-200 rounded-xl cursor-pointer bg-slate-50/50">
                            
                            <small class="text-slate-400 text-[11px] mt-1 block">Tips: Buka via HP untuk langsung memotret fisik surat.</small>
                            @error('lampiran_file') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror

                            <!-- Pratinjau Gambar Hasil Scan -->
                            <div class="mt-3" id="previewContainer" style="display: none;">
                                <p class="text-xs font-semibold text-slate-600 mb-1">Pratinjau Hasil Scan:</p>
                                <img id="imagePreview" src="#" alt="Preview" class="max-h-40 rounded-lg border border-slate-200 shadow-sm">
                            </div>
                        </div>

                        <!-- Lokasi Fisik -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Lokasi Arsip Fisik</label>
                            <input type="text" name="lokasi_arsip_fisik" value="{{ old('lokasi_arsip_fisik') }}" placeholder="Contoh: Rak A-3 Box 12"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500 outline-none transition duration-150">
                            @error('lokasi_arsip_fisik') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 px-6 py-4 bg-slate-50 border-t border-slate-100">
                <a href="{{ route('surat-masuk.index') }}" class="px-4 py-2.5 text-xs font-semibold text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-100 hover:text-slate-800 transition">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2.5 text-xs font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-md shadow-blue-600/30 transition">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Surat Masuk
                </button>
            </div>

        </div>
    </form>
</div>

<!-- Script untuk Validasi Ukuran File & Menampilkan Preview Gambar -->
<script>
    document.getElementById('lampiranFile').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const previewContainer = document.getElementById('previewContainer');
        const imagePreview = document.getElementById('imagePreview');

        if (file) {
            // Batasan maksimal 10MB (10 * 1024 * 1024 bytes)
            if (file.size > 10 * 1024 * 1024) {
                alert('Ukuran file terlalu besar! Maksimal ukuran yang diizinkan adalah 10MB.');
                this.value = '';
                previewContainer.style.display = 'none';
                return;
            }

            // Tampilkan preview jika formatnya berupa gambar
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    previewContainer.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                // Sembunyikan preview jika filenya PDF
                previewContainer.style.display = 'none';
            }
        }
    });
</script>
@endsection