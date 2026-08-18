@extends('layouts.app')

@section('title', 'Disposisi Surat')

@section('content')
<div class="space-y-6">

    <!-- HEADER & BARIS TOMBOL AKSI -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Disposisi Surat</h1>
            <p class="text-sm text-slate-500 mt-0.5">Kelola dan pantau instruksi disposisi dari pimpinan ke unit kerja.</p>
        </div>
    </div>

    <!-- FILTER SECTION -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5">
        <form method="GET" action="{{ route('disposisi.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
            
            <!-- Input Pencarian -->
            <div class="lg:col-span-5 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Cari penerima atau isi instruksi..." 
                    class="w-full pl-10 pr-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150 text-slate-700 placeholder-slate-400">
            </div>

            <!-- Filter Sifat Disposisi -->
            <div class="lg:col-span-3">
                <select name="sifat" class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150 text-slate-700 font-medium">
                    <option value="">Semua Sifat</option>
                    @foreach(['biasa', 'penting', 'segera', 'rahasia'] as $sf)
                        <option value="{{ $sf }}" @selected(request('sifat') == $sf)>{{ ucfirst($sf) }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Tanggal -->
            <div class="lg:col-span-3">
                <input type="date" name="tanggal" value="{{ request('tanggal') }}" 
                    class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150 text-slate-700 font-medium">
            </div>

            <!-- Tombol Submit Filter & Reset -->
            <div class="lg:col-span-1 flex gap-1.5">
                <button type="submit" title="Terapkan Filter" class="w-full bg-slate-900 hover:bg-slate-800 text-white p-2.5 rounded-xl text-sm font-semibold shadow-sm transition flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                </button>
                
                @if(request()->anyFilled(['search', 'sifat', 'tanggal']))
                    <a href="{{ route('disposisi.index') }}" title="Reset Filter" 
                       class="bg-slate-100 hover:bg-slate-200 text-slate-600 p-2.5 rounded-xl text-sm font-semibold transition flex items-center justify-center">
                        ✕
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- TABEL DATA -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        <th class="px-5 py-3.5">Surat Asal</th>
                        <th class="px-5 py-3.5">Tgl Disposisi</th>
                        <th class="px-5 py-3.5">Tujuan / Penerima</th>
                        <th class="px-5 py-3.5">Isi Instruksi</th>
                        <th class="px-5 py-3.5">Sifat</th>
                        <th class="px-5 py-3.5">Batas Waktu</th>
                        <th class="px-5 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($disposisis ?? [] as $d)
                    <tr class="hover:bg-slate-50/80 transition duration-150">
                        <td class="px-5 py-4 font-medium whitespace-nowrap">
                            @if($d->suratMasuk)
                                <a href="{{ route('surat-masuk.show', $d->suratMasuk) }}" class="text-blue-600 hover:text-blue-700 font-bold hover:underline inline-flex items-center gap-1">
                                    <span>#{{ $d->suratMasuk->nomor_agenda ?? '-' }}</span>
                                    <span class="text-xs text-slate-400 font-normal">({{ $d->suratMasuk->nomor_surat ?? '-' }})</span>
                                </a>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-slate-500 whitespace-nowrap">
                            {{ optional($d->tanggal_disposisi)->format('d/m/Y') ?? ($d->tanggal_disposisi ? \Carbon\Carbon::parse($d->tanggal_disposisi)->format('d/m/Y') : '-') }}
                        </td>
                        <td class="px-5 py-4 text-slate-800">
                            <span class="inline-block px-2.5 py-1 rounded-lg bg-slate-100 border border-slate-200 text-xs font-medium">
                                {{ $d->tujuan ?? $d->penerima ?? '-' }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-slate-700 max-w-xs truncate font-medium" title="{{ $d->isi_disposisi }}">
                            {{ $d->isi_disposisi }}
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap">
                            @php
                                $sifatClass = match(strtolower($d->sifat ?? '')) {
                                    'segera'  => 'bg-rose-50 text-rose-700 border-rose-200',
                                    'penting' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'rahasia' => 'bg-purple-50 text-purple-700 border-purple-200',
                                    'biasa'   => 'bg-blue-50 text-blue-700 border-blue-200',
                                    default   => 'bg-slate-100 text-slate-600 border-slate-200'
                                };
                            @endphp
                            <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold border {{ $sifatClass }}">
                                {{ ucfirst($d->sifat ?? 'Biasa') }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-slate-500 whitespace-nowrap">
                            {{ optional($d->batas_waktu)->format('d/m/Y') ?? ($d->batas_waktu ? \Carbon\Carbon::parse($d->batas_waktu)->format('d/m/Y') : '-') }}
                        </td>
                        <td class="px-5 py-4 text-center whitespace-nowrap">
                            <div class="inline-flex items-center gap-1">
                                <a href="{{ route('disposisi.show', $d) }}" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Lihat Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('disposisi.edit', $d) }}" class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition" title="Ubah Disposisi">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('disposisi.destroy', $d) }}" method="POST" class="inline">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="button" 
                                            onclick="confirmDelete(event, 'Disposisi ini akan dihapus secara permanen!')" 
                                            class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" 
                                            title="Hapus Disposisi">
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
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <p class="text-slate-700 font-semibold text-base">Belum ada data disposisi</p>
                                <p class="text-slate-400 text-xs mt-1 mb-4">Disposisi dibuat melalui daftar aksi pada menu Surat Masuk.</p>
                                <a href="{{ route('surat-masuk.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold transition shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    <span>Buka Surat Masuk</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION FOOTER -->
        @if(isset($disposisis) && method_exists($disposisis, 'hasPages') && $disposisis->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">
                {{ $disposisis->withQueryString()->links() }}
            </div>
        @endif
    </div>

</div>
@endsection