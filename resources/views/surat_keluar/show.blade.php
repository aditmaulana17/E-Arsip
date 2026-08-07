@extends('layouts.app')
@section('title', 'Detail Surat Keluar')
@section('content')
<div class="bg-white rounded-xl shadow-sm p-6 max-w-3xl">
    <h3 class="text-lg font-bold text-slate-800">{{ $suratKeluar->perihal }}</h3>
    <p class="text-sm text-slate-400 mb-4">{{ $suratKeluar->nomor_surat }}</p>
    <dl class="grid grid-cols-2 gap-4 text-sm">
        <div><dt class="text-slate-400">Tujuan</dt><dd class="font-medium">{{ $suratKeluar->instansi->nama_instansi }}</dd></div>
        <div><dt class="text-slate-400">Kategori</dt><dd class="font-medium">{{ $suratKeluar->kategori->nama_kategori }}</dd></div>
        <div><dt class="text-slate-400">Tanggal Surat</dt><dd class="font-medium">{{ $suratKeluar->tanggal_surat->format('d-m-Y') }}</dd></div>
        <div><dt class="text-slate-400">Dibuat Oleh</dt><dd class="font-medium">{{ $suratKeluar->pembuat->name ?? '-' }}</dd></div>
        <div><dt class="text-slate-400">Ditandatangani</dt><dd class="font-medium">{{ $suratKeluar->penandatangan->name ?? '-' }}</dd></div>
        <div><dt class="text-slate-400">Status</dt><dd class="font-medium">{{ ucfirst($suratKeluar->status) }}</dd></div>
    </dl>
    <div class="mt-4">
        <dt class="text-slate-400 text-sm">Ringkasan</dt>
        <dd class="mt-1">{{ $suratKeluar->ringkasan ?? '-' }}</dd>
    </div>
    @if($suratKeluar->lampiran_file)
        <a href="{{ \Illuminate\Support\Facades\Storage::url($suratKeluar->lampiran_file) }}" target="_blank" class="inline-block mt-4 text-sm text-blue-600 underline">📎 Lihat File Surat</a>
    @endif
</div>
@endsection
