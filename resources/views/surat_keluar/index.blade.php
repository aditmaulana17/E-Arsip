@extends('layouts.app')
@section('title', 'Surat Keluar')

@section('content')
<div class="space-y-6">

    <!-- HEADER & BARIS TOMBOL AKSI -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Surat Keluar</h1>
            <p class="text-sm text-slate-500">Kelola dan pantau penerbitan surat keluar organisasi Anda.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <!-- Export Excel -->
            <a href="{{ route('export.surat-keluar.excel', request()->query()) }}" 
               class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium bg-emerald-50 text-emerald-700 hover:bg-emerald-100 rounded-lg transition-colors border border-emerald-200 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Excel
            </a>

            <!-- Export PDF -->
            <a href="{{ route('export.surat-keluar.pdf', request()->query()) }}" 
               class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium bg-rose-50 text-rose-700 hover:bg-rose-100 rounded-lg transition-colors border border-rose-200 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                PDF
            </a>

            <!-- Buat Surat Keluar -->
            <a href="{{ route('surat-keluar.create') }}" 
               class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold bg-brand-600 text-white hover:bg-brand-700 rounded-lg transition-all shadow-sm hover:shadow">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Buat Surat Keluar
            </a>
        </div>
    </div>

    <!-- FILTER SECTION -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200/80 p-4">
        <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-6 gap-3">
            <!-- Search -->
            <div class="md:col-span-2 relative">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari perihal/nomor surat..." 
                       class="w-full rounded-lg border-slate-300 text-sm focus:ring-brand-500 focus:border-brand-500">
            </div>

            <!-- Kategori -->
            <div>
                <select name="kategori_id" class="w-full rounded-lg border-slate-300 text-sm focus:ring-brand-500 focus:border-brand-500">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $k)
                        <option value="{{ $k->id }}" @selected(request('kategori_id') == $k->id)>{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status -->
            <div>
                <select name="status" class="w-full rounded-lg border-slate-300 text-sm focus:ring-brand-500 focus:border-brand-500">
                    <option value="">Semua Status</option>
                    @foreach(['draf','diproses','disetujui','dikirim','diarsipkan'] as $st)
                        <option value="{{ $st }}" @selected(request('status') == $st)>{{ ucfirst($st) }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Dari Tanggal -->
            <div>
                <input type="date" name="dari_tanggal" value="{{ request('dari_tanggal') }}" 
                       class="w-full rounded-lg border-slate-300 text-sm focus:ring-brand-500 focus:border-brand-500">
            </div>

            <!-- Submit Filter & Reset -->
            <div class="flex gap-1.5">
                <button type="submit" class="w-full bg-slate-800 text-white rounded-lg text-sm font-medium hover:bg-slate-900 transition-colors py-2">
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'kategori_id', 'status', 'dari_tanggal']))
                    <a href="{{ route('surat-keluar.index') }}" title="Reset Filter" 
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
                        <th class="px-4 py-3.5">Nomor Surat</th>
                        <th class="px-4 py-3.5">Tgl Surat</th>
                        <th class="px-4 py-3.5">Tujuan</th>
                        <th class="px-4 py-3.5">Perihal</th>
                        <th class="px-4 py-3.5">Kategori</th>
                        <th class="px-4 py-3.5">Status</th>
                        <th class="px-4 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($suratKeluars as $s)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-4 py-3 font-medium text-brand-600 whitespace-nowrap">{{ $s->nomor_surat }}</td>
                        <td class="px-4 py-3 text-slate-600 whitespace-nowrap">{{ $s->tanggal_surat ? $s->tanggal_surat->format('d/m/Y') : '-' }}</td>
                        <td class="px-4 py-3 text-slate-700">
                            <span class="inline-block px-2 py-0.5 rounded bg-slate-100 border border-slate-200 text-xs">
                                {{ $s->tujuan ?? $s->instansi->nama_instansi ?? '-' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-slate-700 max-w-xs truncate" title="{{ $s->perihal }}">{{ $s->perihal }}</td>
                        <td class="px-4 py-3 text-slate-600 whitespace-nowrap">{{ $s->kategori->nama_kategori ?? '-' }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            @php
                                $badgeClass = match($s->status) {
                                    'draf'         => 'bg-slate-100 text-slate-700 border-slate-200',
                                    'diproses'     => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'disetujui'    => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'dikirim'      => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'diarsipkan'   => 'bg-purple-50 text-purple-700 border-purple-200',
                                    default        => 'bg-slate-100 text-slate-600 border-slate-200'
                                };
                            @endphp
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full border {{ $badgeClass }}">
                                {{ ucfirst($s->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right whitespace-nowrap">
                            <div class="inline-flex items-center gap-1.5">
                                <a href="{{ route('surat-keluar.show', $s) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('surat-keluar.edit', $s) }}" class="p-1.5 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Ubah Data">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('surat-keluar.destroy', $s) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus surat keluar ini?')">
                                    @csrf @method('DELETE')
                                    <button class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus Surat">
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
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                </div>
                                <p class="text-slate-600 font-medium">Belum ada surat keluar</p>
                                <p class="text-slate-400 text-xs mt-1">Coba sesuaikan pencarian/filter Anda atau buat surat keluar baru.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- PAGINATION -->
    <div class="pt-2">
        {{ $suratKeluars->links() }}
    </div>

</div>
@endsection