@extends('layouts.app')

@section('title', 'Detail Surat Masuk')

@section('content')
<div class="space-y-4 sm:space-y-6" x-data="scanUploadModal()">

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
                        {{ $suratMasuk->tanggal_surat ? \Carbon\Carbon::parse($suratMasuk->tanggal_surat)->format('d-m-Y') : '-' }}
                    </dd>
                </div>
                <div>
                    <dt class="font-bold uppercase tracking-wider text-slate-400 mb-1">Tanggal Diterima</dt>
                    <dd class="text-xs sm:text-sm font-semibold text-slate-700 bg-slate-50 px-3.5 py-2.5 rounded-xl border border-slate-100">
                        {{ $suratMasuk->tanggal_terima ? \Carbon\Carbon::parse($suratMasuk->tanggal_terima)->format('d-m-Y') : '-' }}
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
            @php
                $lampiranPath = $suratMasuk->lampiran_file ?? $suratMasuk->lampiran;
            @endphp

            <div class="pt-4 border-t border-slate-100 space-y-3">
                <div class="flex items-center justify-between">
                    <dt class="text-xs font-bold uppercase tracking-wider text-slate-400">Berkas Lampiran (Digital)</dt>
                    
                    <button type="button" @click="openModal()" class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition border border-blue-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        <span>{{ $lampiranPath ? 'Ganti / Upload Berkas' : '+ Upload / Scan Berkas' }}</span>
                    </button>
                </div>
                
                @if($lampiranPath)
                    @php
                        $extension = pathinfo($lampiranPath, PATHINFO_EXTENSION);
                        $extLower = strtolower($extension);
                        $isImage = in_array($extLower, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
                        $fileUrl = route('surat-masuk.preview', $suratMasuk->id);
                        $downloadUrl = route('surat-masuk.download', $suratMasuk->id);
                    @endphp

                    <div class="flex flex-wrap items-center gap-2">
                        <!-- Buka Tab Baru -->
                        <a href="{{ $fileUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-xs font-semibold bg-blue-50 text-blue-600 px-4 py-2.5 rounded-xl border border-blue-200/80 hover:bg-blue-100 transition shadow-sm grow sm:grow-0 justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <span>Buka Dokumen ({{ strtoupper($extension) }})</span>
                        </a>

                        <!-- Download File -->
                        <a href="{{ $downloadUrl }}" class="inline-flex items-center gap-2 text-xs font-semibold bg-slate-100 text-slate-700 px-4 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-200 transition grow sm:grow-0 justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            <span>Unduh File</span>
                        </a>
                    </div>

                    <!-- Area Embed Preview -->
                    <div class="mt-3 rounded-xl border border-slate-200 overflow-hidden bg-slate-50 p-2">
                        @if($isImage)
                            <img src="{{ $fileUrl }}" alt="Lampiran Surat" class="max-h-[500px] w-full object-contain rounded-lg">
                        @elseif($extLower === 'pdf')
                            <div class="space-y-2">
                                <iframe src="{{ $fileUrl }}" class="w-full h-[500px] rounded-lg border-0 min-h-[350px]"></iframe>
                                <p class="text-[11px] text-slate-400 text-center italic">
                                    Dokumen tidak tampil di browser Anda? <a href="{{ $fileUrl }}" target="_blank" class="text-blue-600 underline font-semibold">Klik di sini untuk membuka / mengunduh langsung.</a>
                                </p>
                            </div>
                        @else
                            <div class="p-4 text-center text-xs text-slate-500">
                                Format file ({{ strtoupper($extension) }}) tidak mendukung pratinjau langsung. Silakan unduh berkas di atas.
                            </div>
                        @endif
                    </div>
                @else
                    <p class="text-xs text-slate-400 italic bg-slate-50 p-3.5 rounded-xl border border-slate-100">Tidak ada lampiran berkas digital.</p>
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
                            {{ $d->dari->name ?? 'Admin' }} <span class="text-purple-600 font-normal mx-0.5">&rarr;</span> {{ $d->kepada->name ?? '-' }}
                        </p>
                        <p class="text-xs text-slate-600 leading-relaxed break-words">{{ $d->instruksi ?? '-' }}</p>
                        <div class="flex items-center justify-between pt-2 border-t border-slate-200/60">
                            <span class="text-[10px] px-2 py-0.5 rounded-full bg-purple-100 text-purple-700 font-semibold">
                                {{ ucfirst($d->status ?? 'Dikirim') }}
                            </span>
                            <span class="text-[10px] text-slate-400 font-medium">
                                {{ $d->created_at ? \Carbon\Carbon::parse($d->created_at)->format('d/m/Y H:i') : '-' }}
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

    <!-- MODAL UPLOAD / SCAN LAMPIRAN -->
    <div x-show="isModalOpen" 
         x-transition.opacity 
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4" 
         style="display: none;">
        
        <div class="bg-white rounded-2xl p-5 sm:p-6 w-full max-w-md shadow-2xl space-y-5 border border-slate-100" @click.away="closeModal()">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-800">Upload / Scan Lampiran</h3>
                <button type="button" @click="closeModal()" class="text-slate-400 hover:text-slate-600 p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form action="{{ route('surat-masuk.upload-lampiran', $suratMasuk->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                
                <!-- Tab Pilihan (File Upload vs Kamera Scan) -->
                <div class="flex bg-slate-100 p-1 rounded-xl gap-1 text-xs font-semibold">
                    <button type="button" @click="setMode('file')" :class="mode === 'file' ? 'bg-white text-blue-600 shadow-xs' : 'text-slate-500 hover:text-slate-800'" class="flex-1 py-2 rounded-lg transition text-center">
                        Pilih File
                    </button>
                    <button type="button" @click="setMode('camera')" :class="mode === 'camera' ? 'bg-white text-blue-600 shadow-xs' : 'text-slate-500 hover:text-slate-800'" class="flex-1 py-2 rounded-lg transition text-center">
                        Scan Kamera
                    </button>
                </div>

                <!-- Input Hidden untuk File dari Kamera -->
                <input type="file" name="lampiran_file" x-ref="fileInput" accept=".pdf,.png,.jpg,.jpeg" class="hidden" @change="onFileSelected($event)">

                <!-- Opsi 1: Upload File Normal -->
                <div x-show="mode === 'file'" class="space-y-3">
                    <div @click="$refs.fileInput.click()" class="border-2 border-dashed border-slate-200 rounded-xl p-6 text-center cursor-pointer hover:border-blue-400 bg-slate-50 hover:bg-blue-50/50 transition">
                        <svg class="w-8 h-8 text-slate-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                        <p class="text-xs font-semibold text-slate-700" x-text="fileName ? fileName : 'Klik untuk memilih berkas'"></p>
                        <p class="text-[10px] text-slate-400 mt-1">Format: PDF, JPG, PNG (Maks 5MB)</p>
                    </div>
                </div>

                <!-- Opsi 2: WebRTC Camera Scan -->
                <div x-show="mode === 'camera'" class="space-y-3">
                    <div class="relative bg-black rounded-xl overflow-hidden aspect-video flex items-center justify-center">
                        <video x-ref="video" autoplay playsinline class="w-full h-full object-cover" x-show="!capturedImage"></video>
                        <img :src="capturedImage" x-show="capturedImage" class="w-full h-full object-contain">
                    </div>

                    <div class="flex gap-2">
                        <template x-if="!capturedImage">
                            <button type="button" @click="takePhoto()" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold transition flex items-center justify-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Tangkap Foto
                            </button>
                        </template>
                        <template x-if="capturedImage">
                            <button type="button" @click="retakePhoto()" class="w-full py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-xl text-xs font-semibold transition">
                                Ambil Ulang Foto
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Footer Tombol Modal -->
                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" @click="closeModal()" class="px-4 py-2 text-xs font-semibold bg-slate-100 text-slate-600 hover:bg-slate-200 rounded-xl transition">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 text-xs font-semibold bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition shadow-sm">
                        Simpan Berkas
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    function scanUploadModal() {
        return {
            isModalOpen: false,
            mode: 'file', // 'file' atau 'camera'
            fileName: '',
            capturedImage: null,
            stream: null,

            openModal() {
                this.isModalOpen = true;
            },

            closeModal() {
                this.stopCamera();
                this.isModalOpen = false;
                this.fileName = '';
                this.capturedImage = null;
            },

            setMode(mode) {
                this.mode = mode;
                if (mode === 'camera') {
                    this.startCamera();
                } else {
                    this.stopCamera();
                }
            },

            startCamera() {
                this.capturedImage = null;
                this.$nextTick(() => {
                    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
                        .then(stream => {
                            this.stream = stream;
                            this.$refs.video.srcObject = stream;
                        })
                        .catch(err => {
                            alert('Gagal mengakses kamera: ' + err.message);
                        });
                });
            },

            stopCamera() {
                if (this.stream) {
                    this.stream.getTracks().forEach(track => track.stop());
                    this.stream = null;
                }
            },

            takePhoto() {
                const canvas = document.createElement('canvas');
                canvas.width = this.$refs.video.videoWidth;
                canvas.height = this.$refs.video.videoHeight;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(this.$refs.video, 0, 0);

                this.capturedImage = canvas.toDataURL('image/jpeg');

                canvas.toBlob(blob => {
                    const file = new File([blob], 'scan_surat_' + Date.now() + '.jpg', { type: 'image/jpeg' });
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    this.$refs.fileInput.files = dataTransfer.files;
                    this.fileName = file.name;
                }, 'image/jpeg');

                this.stopCamera();
            },

            retakePhoto() {
                this.capturedImage = null;
                this.startCamera();
            },

            onFileSelected(event) {
                if (event.target.files.length > 0) {
                    this.fileName = event.target.files[0].name;
                }
            }
        }
    }
</script>
@endsection