@extends('layouts.app')
@section('title', 'Detail Surat Masuk')
@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h3 class="text-lg font-bold text-slate-800">{{ $suratMasuk->perihal }}</h3>
                <p class="text-sm text-slate-400">{{ $suratMasuk->nomor_agenda }} &middot; {{ $suratMasuk->nomor_surat }}</p>
            </div>
            <a href="{{ route('surat-masuk.label', $suratMasuk) }}" target="_blank" class="text-sm px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200">🖨 Cetak Label</a>
        </div>
        <dl class="grid grid-cols-2 gap-4 text-sm">
            <div><dt class="text-slate-400">Instansi Pengirim</dt><dd class="font-medium">{{ $suratMasuk->instansi->nama_instansi }}</dd></div>
            <div><dt class="text-slate-400">Kategori</dt><dd class="font-medium">{{ $suratMasuk->kategori->nama_kategori }}</dd></div>
            <div><dt class="text-slate-400">Tanggal Surat</dt><dd class="font-medium">{{ $suratMasuk->tanggal_surat->format('d-m-Y') }}</dd></div>
            <div><dt class="text-slate-400">Tanggal Diterima</dt><dd class="font-medium">{{ $suratMasuk->tanggal_terima->format('d-m-Y') }}</dd></div>
            <div><dt class="text-slate-400">Diterima Oleh</dt><dd class="font-medium">{{ $suratMasuk->penerima->name ?? '-' }}</dd></div>
            <div><dt class="text-slate-400">Lokasi Arsip Fisik</dt><dd class="font-medium">{{ $suratMasuk->lokasi_arsip_fisik ?? '-' }}</dd></div>
        </dl>
        <div class="mt-4">
            <dt class="text-slate-400 text-sm">Ringkasan</dt>
            <dd class="mt-1">{{ $suratMasuk->ringkasan ?? '-' }}</dd>
        </div>
        @if($suratMasuk->lampiran_file)
            <a href="{{ \Illuminate\Support\Facades\Storage::url($suratMasuk->lampiran_file) }}" target="_blank" class="inline-block mt-4 text-sm text-blue-600 underline">📎 Lihat Lampiran</a>
        @endif
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-semibold text-slate-700">Riwayat Disposisi</h3>
            <a href="{{ route('disposisi.create', $suratMasuk) }}" class="text-xs px-2 py-1 rounded-lg bg-purple-50 text-purple-600 hover:bg-purple-100">+ Disposisi</a>
        </div>
        <div class="space-y-3">
            @forelse($suratMasuk->disposisi as $d)
                <div class="border-l-4 border-purple-300 pl-3 py-1">
                    <p class="text-sm font-medium">{{ $d->dari->name }} → {{ $d->kepada->name }}</p>
                    <p class="text-xs text-slate-500">{{ $d->instruksi }}</p>
                    <span class="text-xs px-2 py-0.5 rounded-full bg-amber-50 text-amber-600">{{ ucfirst($d->status) }}</span>
                </div>
            @empty
                <p class="text-sm text-slate-400">Belum ada disposisi.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
