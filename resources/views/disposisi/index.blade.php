@extends('layouts.app')
@section('title', 'Disposisi Surat')

@section('content')
<div class="space-y-6">

    <!-- HEADER & BARIS TOMBOL AKSI -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Disposisi Surat</h1>
            <p class="text-sm text-slate-500">Kelola dan pantau instruksi disposisi dari pimpinan ke unit kerja.</p>
        </div>
    </div>

    <!-- FILTER SECTION -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200/80 p-4">
        <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3">
            <!-- Search -->
            <div class="md:col-span-2 relative">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari penerima / isi instruksi..." 
                       class="w-full rounded-lg border-slate-300 text-sm focus:ring-brand-500 focus:border-brand-500">
            </div>

            <!-- Sifat Disposisi -->
            <div>
                <select name="sifat" class="w-full rounded-lg border-slate-300 text-sm focus:ring-brand-500 focus:border-brand-500">
                    <option value="">Semua Sifat</option>
                    @foreach(['biasa', 'penting', 'segera', 'rahasia'] as $sf)
                        <option value="{{ $sf }}" @selected(request('sifat') == $sf)>{{ ucfirst($sf) }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Tanggal Disposisi -->
            <div>
                <input type="date" name="tanggal" value="{{ request('tanggal') }}" 
                       class="w-full rounded-lg border-slate-300 text-sm focus:ring-brand-500 focus:border-brand-500">
            </div>

            <!-- Submit Filter & Reset -->
            <div class="flex gap-1.5">
                <button type="submit" class="w-full bg-slate-800 text-white rounded-lg text-sm font-medium hover:bg-slate-900 transition-colors py-2">
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'sifat', 'tanggal']))
                    <a href="{{ route('disposisi.index') }}" title="Reset Filter" 
                       class="px-2.5 py-2 bg-slate-100 text-slate-600 rounded-lg text-sm hover:bg-slate-200 transition-colors flex items-center justify-center">
                        ✕
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- TABEL DATA -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-600 font-semibold uppercase text-xs tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3.5">Surat Asal</th>
                        <th class="px-4 py-3.5">Tgl Disposisi</th>
                        <th class="px-4 py-3.5">Tujuan / Penerima</th>
                        <th class="px-4 py-3.5">Isi Instruksi</th>
                        <th class="px-4 py-3.5">Sifat</th>
                        <th class="px-4 py-3.5">Batas Waktu</th>
                        <th class="px-4 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($disposisis ?? [] as $d)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-4 py-3 font-medium whitespace-nowrap">
                            <a href="{{ route('surat-masuk.show', $d->suratMasuk) }}" class="text-brand-600 hover:underline flex items-center gap-1">
                                <span>#{{ $d->suratMasuk->nomor_agenda ?? '-' }}</span>
                                <span class="text-xs text-slate-400">({{ $d->suratMasuk->nomor_surat ?? '-' }})</span>
                            </a>
                        </td>
                        <td class="px-4 py-3 text-slate-600 whitespace-nowrap">
                            {{ $d->tanggal_disposisi ? \Carbon\Carbon::parse($d->tanggal_disposisi)->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-4 py-3 text-slate-800 font-medium">
                            <span class="inline-block px-2.5 py-1 rounded-md bg-purple-50 text-purple-700 border border-purple-200 text-xs">
                                {{ $d->tujuan ?? $d->penerima ?? '-' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-slate-700 max-w-xs truncate" title="{{ $d->isi_disposisi }}">
                            {{ $d->isi_disposisi }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            @php
                                $sifatClass = match(strtolower($d->sifat ?? '')) {
                                    'segera'   => 'bg-rose-50 text-rose-700 border-rose-200',
                                    'penting'  => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'rahasia'  => 'bg-purple-50 text-purple-700 border-purple-200',
                                    'biasa'    => 'bg-blue-50 text-blue-700 border-blue-200',
                                    default    => 'bg-slate-100 text-slate-600 border-slate-200'
                                };
                            @endphp
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full border {{ $sifatClass }}">
                                {{ ucfirst($d->sifat ?? 'Biasa') }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-slate-600 whitespace-nowrap">
                            {{ $d->batas_waktu ? \Carbon\Carbon::parse($d->batas_waktu)->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-4 py-3 text-right whitespace-nowrap">
                            <div class="inline-flex items-center gap-1.5">
                                <a href="{{ route('disposisi.show', $d) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('disposisi.edit', $d) }}" class="p-1.5 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Ubah Disposisi">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('disposisi.destroy', $d) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus disposisi ini?')">
                                    @csrf @method('DELETE')
                                    <button class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus Disposisi">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-12">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                </div>
                                <p class="text-slate-600 font-medium">Belum ada data disposisi</p>
                                <p class="text-slate-400 text-xs mt-1">Disposisi dibuat melalui daftar aksi pada menu Surat Masuk.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- PAGINATION -->
    @if(isset($disposisis) && method_exists($disposisis, 'links'))
    <div class="pt-2">
        {{ $disposisis->links() }}
    </div>
    @endif

</div>
@endsection