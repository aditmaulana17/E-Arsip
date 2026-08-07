@extends('layouts.app')
@section('title', 'Kategori Surat')

@section('content')
<div class="space-y-6">

    <!-- HEADER & TOMBOL TAMBAH -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Kategori Surat</h1>
            <p class="text-sm text-slate-500">Kelola master data kategori dan klasifikasi jenis surat.</p>
        </div>

        <button type="button" 
                onclick="document.getElementById('modalTambah').classList.remove('hidden')" 
                class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold bg-brand-600 text-white hover:bg-brand-700 rounded-xl transition-all shadow-sm hover:shadow self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Kategori
        </button>
    </div>

    <!-- TABEL DATA -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-600 font-semibold uppercase text-xs tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-3.5">Nama Kategori</th>
                        <th class="px-4 py-3.5">Kode</th>
                        <th class="px-4 py-3.5">Sifat</th>
                        <th class="px-4 py-3.5 text-center">Jml Surat Masuk</th>
                        <th class="px-4 py-3.5 text-center">Jml Surat Keluar</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($kategoris as $k)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <!-- Nama Kategori -->
                        <td class="px-5 py-3.5 font-semibold text-slate-800">
                            {{ $k->nama_kategori }}
                        </td>

                        <!-- Kode -->
                        <td class="px-4 py-3.5 whitespace-nowrap">
                            <span class="inline-block px-2.5 py-0.5 rounded-md bg-slate-100 border border-slate-200 text-slate-700 text-xs font-mono font-medium">
                                {{ $k->kode }}
                            </span>
                        </td>

                        <!-- Sifat -->
                        <td class="px-4 py-3.5 whitespace-nowrap">
                            @php
                                $sifatClass = match(strtolower($k->sifat)) {
                                    'penting' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'rahasia' => 'bg-rose-50 text-rose-700 border-rose-200',
                                    default   => 'bg-blue-50 text-blue-700 border-blue-200'
                                };
                            @endphp
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full border {{ $sifatClass }}">
                                {{ ucfirst($k->sifat) }}
                            </span>
                        </td>

                        <!-- Jml Surat Masuk -->
                        <td class="px-4 py-3.5 text-center whitespace-nowrap">
                            <span class="inline-flex items-center justify-center min-w-[28px] h-7 px-2 text-xs font-medium rounded-full bg-slate-100 text-slate-700 border border-slate-200">
                                {{ $k->surat_masuk_count ?? 0 }}
                            </span>
                        </td>

                        <!-- Jml Surat Keluar -->
                        <td class="px-4 py-3.5 text-center whitespace-nowrap">
                            <span class="inline-flex items-center justify-center min-w-[28px] h-7 px-2 text-xs font-medium rounded-full bg-slate-100 text-slate-700 border border-slate-200">
                                {{ $k->surat_keluar_count ?? 0 }}
                            </span>
                        </td>

                        <!-- Aksi -->
                        <td class="px-5 py-3.5 text-right whitespace-nowrap">
                            <div class="inline-flex items-center gap-1.5">
                                <button type="button" 
                                        onclick="document.getElementById('modalEdit{{ $k->id }}').classList.remove('hidden')" 
                                        class="p-1.5 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" 
                                        title="Ubah Kategori">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>

                                <form action="{{ route('kategori.destroy', $k) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus Kategori">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-12">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 11h10M7 15h4M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"/></svg>
                                </div>
                                <p class="text-slate-600 font-medium">Belum ada kategori surat</p>
                                <p class="text-slate-400 text-xs mt-1">Silakan tambahkan data kategori surat baru.</p>
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
        {{ $kategoris->links() }}
    </div>

</div>

<!-- ========================================== -->
<!-- MODAL EDIT (LOOP DI LUAR TABEL) -->
<!-- ========================================== -->
@foreach($kategoris as $k)
<div id="modalEdit{{ $k->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4 overflow-y-auto">
    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden transform transition-all">
        
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-slate-50/50">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-amber-50 text-amber-600 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800">Ubah Kategori Surat</h3>
                    <p class="text-xs text-slate-500">Perbarui rincian informasi kategori surat.</p>
                </div>
            </div>
            <button type="button" onclick="document.getElementById('modalEdit{{ $k->id }}').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Form Edit -->
        <form method="POST" action="{{ route('kategori.update', $k) }}">
            @csrf @method('PUT')
            
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Nama Kategori <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_kategori" value="{{ $k->nama_kategori }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-800 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Kode Surat <span class="text-rose-500">*</span></label>
                        <input type="text" name="kode" value="{{ $k->kode }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-mono text-slate-800 uppercase focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Sifat Default <span class="text-rose-500">*</span></label>
                        <select name="sifat" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-800 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                            @foreach(['biasa','penting','rahasia'] as $s)
                                <option value="{{ $s }}" @selected(strtolower($k->sifat) == $s)>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Keterangan <span class="text-slate-400 font-normal lowercase">(opsional)</span></label>
                    <textarea name="keterangan" rows="3" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-800 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all resize-none">{{ $k->keterangan }}</textarea>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end gap-2 px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                <button type="button" onclick="document.getElementById('modalEdit{{ $k->id }}').classList.add('hidden')" class="px-4 py-2.5 text-sm font-medium text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-colors">Batal</button>
                <button type="submit" class="inline-flex items-center gap-1.5 px-5 py-2.5 text-sm font-semibold bg-brand-600 text-white hover:bg-brand-700 rounded-xl transition-all shadow-sm">Simpan Perubahan</button>
            </div>
        </form>

    </div>
</div>
@endforeach

<!-- ========================================== -->
<!-- MODAL TAMBAH -->
<!-- ========================================== -->
<div id="modalTambah" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4 overflow-y-auto">
    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden transform transition-all">
        
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-slate-50/50">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-brand-50 text-brand-600 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 11h10M7 15h4M5 3h14a2 2 0 012-2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800">Tambah Kategori Surat</h3>
                    <p class="text-xs text-slate-500">Isi formulir untuk menambahkan kategori baru.</p>
                </div>
            </div>
            <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Form Tambah -->
        <form method="POST" action="{{ route('kategori.store') }}">
            @csrf
            
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Nama Kategori <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_kategori" placeholder="Contoh: Surat Undangan, Surat Perjanjian" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-800 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Kode Surat <span class="text-rose-500">*</span></label>
                        <input type="text" name="kode" placeholder="Contoh: SK, UND, MOU" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-mono text-slate-800 uppercase focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Sifat Default <span class="text-rose-500">*</span></label>
                        <select name="sifat" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-800 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                            <option value="biasa">Biasa</option>
                            <option value="penting">Penting</option>
                            <option value="rahasia">Rahasia</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Keterangan <span class="text-slate-400 font-normal lowercase">(opsional)</span></label>
                    <textarea name="keterangan" rows="3" placeholder="Penjelasan singkat mengenai jenis kategori ini..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-800 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all resize-none"></textarea>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end gap-2 px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')" class="px-4 py-2.5 text-sm font-medium text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-colors">Batal</button>
                <button type="submit" class="inline-flex items-center gap-1.5 px-5 py-2.5 text-sm font-semibold bg-brand-600 text-white hover:bg-brand-700 rounded-xl transition-all shadow-sm">Simpan Data</button>
            </div>
        </form>

    </div>
</div>
@endsection