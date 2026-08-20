@extends('layouts.app')

@section('title', 'Detail Surat Masuk - ' . $suratMasuk->nomor_agenda)

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Breadcrumb & Action Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Detail Surat Masuk</h4>
            <p class="text-muted mb-0">Nomor Agenda: <strong>{{ $suratMasuk->nomor_agenda }}</strong></p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('surat-masuk.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
            <a href="{{ route('surat-masuk.edit', $suratMasuk->id) }}" class="btn btn-warning text-white">
                <i class="bi bi-pencil me-1"></i> Edit Surat
            </a>
            <a href="{{ route('surat-masuk.cetak-disposisi', $suratMasuk->id) }}" target="_blank" class="btn btn-danger">
                <i class="bi bi-printer me-1"></i> Cetak Disposisi
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Kolom Informasi Surat -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white fw-bold py-3">
                    <i class="bi bi-info-circle me-1 text-primary"></i> Informasi Surat
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-hover align-middle mb-0">
                        <tbody>
                            <tr>
                                <th class="text-muted w-40">Nomor Agenda</th>
                                <td>: <span class="badge bg-primary fs-6">{{ $suratMasuk->nomor_agenda }}</span></td>
                            </tr>
                            <tr>
                                <th class="text-muted">Nomor Surat</th>
                                <td>: {{ $suratMasuk->nomor_surat ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Asal Instansi</th>
                                <td>: {{ $suratMasuk->instansi->nama_instansi ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Kategori Surat</th>
                                <td>: {{ $suratMasuk->kategori->nama_kategori ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Tanggal Surat</th>
                                <td>: {{ \Carbon\Carbon::parse($suratMasuk->tanggal_surat)->translatedFormat('d F Y') }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Tanggal Terima</th>
                                <td>: {{ \Carbon\Carbon::parse($suratMasuk->tanggal_terima)->translatedFormat('d F Y') }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Penerima</th>
                                <td>: {{ $suratMasuk->penerima->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Perihal</th>
                                <td>: {{ $suratMasuk->perihal }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Ringkasan / Isi</th>
                                <td>: {{ $suratMasuk->ringkasan ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Kolom Pratinjau Lampiran (Hanya Preview & Download) -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white d-flex justify-content-between align-middle py-3">
                    <span class="fw-bold">
                        <i class="bi bi-file-earmark-pdf me-1 text-danger"></i> BERKAS LAMPIRAN (DIGITAL)
                    </span>
                    @php
                        $filePath = $suratMasuk->lampiran_file ?? $suratMasuk->lampiran;
                    @endphp
                    @if ($filePath)
                        <div class="d-flex gap-2">
                            <a href="{{ route('surat-masuk.preview', $suratMasuk->id) }}" target="_blank" class="btn btn-sm btn-primary">
                                <i class="bi bi-box-arrow-up-right me-1"></i> Lihat Surat Di Tab Baru
                            </a>
                            <a href="{{ route('surat-masuk.download', $suratMasuk->id) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-download me-1"></i> Unduh Berkas
                            </a>
                        </div>
                    @endif
                </div>
                <div class="card-body p-0 d-flex flex-column" style="min-height: 550px;">
                    @if ($filePath)
                        @php
                            $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                        @endphp

                        @if (in_array($extension, ['pdf']))
                            <!-- Viewer Dokumen PDF Embed Direct -->
                            <iframe 
                                src="{{ route('surat-masuk.preview', $suratMasuk->id) }}" 
                                class="w-100 flex-grow-1 border-0 rounded-bottom" 
                                style="min-height: 550px;"
                                title="Pratinjau PDF">
                            </iframe>
                        @elseif (in_array($extension, ['jpg', 'jpeg', 'png', 'webp']))
                            <!-- Viewer Gambar Direct -->
                            <div class="text-center p-3 flex-grow-1 d-flex align-items-center justify-content-center bg-light rounded-bottom">
                                <img src="{{ route('surat-masuk.preview', $suratMasuk->id) }}" 
                                     alt="Lampiran Surat Masuk" 
                                     class="img-fluid rounded shadow-sm" 
                                     style="max-height: 550px; object-fit: contain;">
                            </div>
                        @else
                            <!-- Tampilan jika format file bukan gambar/PDF (misal docx, xlsx, dll) -->
                            <div class="text-center my-auto py-5">
                                <i class="bi bi-file-earmark-arrow-down text-muted display-4"></i>
                                <p class="mt-2 text-muted">Format berkas (<code>.{{ $extension }}</code>) tidak dapat ditayangkan langsung di browser.</p>
                                <a href="{{ route('surat-masuk.download', $suratMasuk->id) }}" class="btn btn-primary mt-2">
                                    <i class="bi bi-download me-1"></i> Unduh Dokumen
                                </a>
                            </div>
                        @endif
                    @else
                        <!-- Tampilan Jika Berkas Lampiran Belum Ada -->
                        <div class="text-center my-auto py-5 text-muted">
                            <i class="bi bi-file-earmark-x display-3 text-secondary"></i>
                            <h6 class="mt-3">Tidak Ada Lampiran Berkas</h6>
                            <p class="small text-muted mb-3">Lampiran berkas dapat ditambahkan melalui menu Edit Surat.</p>
                            <a href="{{ route('surat-masuk.edit', $suratMasuk->id) }}" class="btn btn-sm btn-outline-warning">
                                <i class="bi bi-pencil me-1"></i> Edit Surat Untuk Tambah Lampiran
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection