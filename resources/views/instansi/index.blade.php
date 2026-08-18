@extends('layouts.app')

@section('title', 'Data Instansi')

@section('content')
<div class="space-y-6">

    <!-- HEADER & TOMBOL TAMBAH -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Data Instansi</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola daftar instansi pengirim dan penerima surat.</p>
        </div>

        <button type="button" 
                onclick="document.getElementById('modalTambah').classList.remove('hidden')" 
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold bg-blue-600 text-white hover:bg-blue-700 rounded-xl transition-colors shadow-sm self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Instansi
        </button>
    </div>

    <!-- FILTER & SEARCH SECTION -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200/80 p-4">
        <form method="GET" action="{{ route('instansi.index') }}" class="flex flex-col sm:flex-row items-center gap-3">
            <div class="relative w-full sm:w-80">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari nama instansi..." 
                       class="w-full pl-9 pr-3 py-2 rounded-lg border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button type="submit" class="w-full sm:w-auto px-5 py-2 bg-slate-900 text-white rounded-lg text-sm font-medium hover:bg-slate-800 transition-colors shadow-sm">
                    Cari
                </button>

                @if(request()->filled('search'))
                    <a href="{{ route('instansi.index') }}" class="px-3 py-2 bg-slate-100 text-slate-600 rounded-lg text-sm font-medium hover:bg-slate-200 transition-colors border border-slate-200">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- TABEL DATA INSTANSI -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 font-semibold uppercase text-xs tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-4">Nama Instansi</th>
                        <th class="px-5 py-4">Jenis</th>
                        <th class="px-5 py-4">Kontak Person</th>
                        <th class="px-5 py-4">Telepon / Email</th>
                        <th class="px-5 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($instansis as $i)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-5 py-4">
                            <div class="font-semibold text-slate-800">{{ $i->nama_instansi }}</div>
                            @if($i->alamat)
                                <div class="text-xs text-slate-400 truncate max-w-xs mt-0.5" title="{{ $i->alamat }}">{{ $i->alamat }}</div>
                            @endif
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap">
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full border {{ strtolower($i->jenis ?? '') == 'internal' ? 'bg-indigo-50 text-indigo-700 border-indigo-200' : 'bg-blue-50 text-blue-700 border-blue-200' }}">
                                {{ ucfirst($i->jenis ?? 'Eksternal') }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-slate-600 whitespace-nowrap">
                            {{ $i->kontak_person ?? '-' }}
                        </td>
                        <td class="px-5 py-4 text-slate-600 whitespace-nowrap">
                            <div>{{ $i->telepon ?? '-' }}</div>
                            @if($i->email)
                                <div class="text-xs text-slate-400">{{ $i->email }}</div>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-right whitespace-nowrap">
                            <div class="inline-flex items-center gap-1">
                                <!-- Tombol Ubah Modal -->
                                <button type="button" 
                                        onclick="document.getElementById('modalEdit{{ $i->id }}').classList.remove('hidden')" 
                                        class="p-1.5 text-slate-500 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" 
                                        title="Ubah Instansi">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>

                                <!-- Form Hapus Langsung -->
                              <form action="{{ route('instansi.destroy', $i) }}" method="POST" class="inline">
                                @csrf 
                                @method('DELETE')
                                <button type="button" 
                                    onclick="confirmDelete(event, 'Data instansi {{ $i->nama_instansi }} akan dihapus secara permanen!')" 
                                    class="p-1.5 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" 
                                    title="Hapus Instansi">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                         </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-16">
                            <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                <div class="w-14 h-14 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mb-4 border border-slate-200">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                </div>
                                <h3 class="text-base font-semibold text-slate-800">Belum ada data instansi</h3>
                                <p class="text-slate-500 text-xs mt-1 mb-5">Silakan tambahkan data instansi baru.</p>
                                <button type="button" 
                                        onclick="document.getElementById('modalTambah').classList.remove('hidden')" 
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold transition-colors shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Tambah Instansi
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- PAGINATION -->
    @if(isset($instansis) && method_exists($instansis, 'links'))
    <div class="pt-2">
        {{ $instansis->withQueryString()->links() }}
    </div>
    @endif

</div>

<!-- MODAL EDIT DATA -->
@foreach($instansis as $i)
<div id="modalEdit{{ $i->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4 overflow-y-auto">
    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-slate-50/50">
            <h3 class="text-base font-bold text-slate-800">Ubah Data Instansi</h3>
            <button type="button" onclick="document.getElementById('modalEdit{{ $i->id }}').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route('instansi.update', $i) }}" class="p-6 space-y-4">
            @csrf 
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Nama Instansi <span class="text-rose-500">*</span></label>
                <input type="text" name="nama_instansi" value="{{ $i->nama_instansi }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Jenis Instansi</label>
                    <select name="jenis" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="eksternal" {{ ($i->jenis ?? '') == 'eksternal' ? 'selected' : '' }}>Eksternal</option>
                        <option value="internal" {{ ($i->jenis ?? '') == 'internal' ? 'selected' : '' }}>Internal</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Kontak Person</label>
                    <input type="text" name="kontak_person" value="{{ $i->kontak_person }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Telepon</label>
                    <input type="text" name="telepon" value="{{ $i->telepon }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Email</label>
                    <input type="email" name="email" value="{{ $i->email }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Alamat</label>
                <textarea name="alamat" rows="2" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 resize-none">{{ $i->alamat }}</textarea>
            </div>
            <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('modalEdit{{ $i->id }}').classList.add('hidden')" class="px-4 py-2 text-sm text-slate-600 hover:bg-slate-100 rounded-xl">Batal</button>
                <button type="submit" class="px-5 py-2 text-sm font-semibold bg-blue-600 text-white hover:bg-blue-700 rounded-xl">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endforeach

<!-- MODAL TAMBAH DATA -->
<div id="modalTambah" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4 overflow-y-auto">
    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-slate-50/50">
            <h3 class="text-base font-bold text-slate-800">Tambah Instansi Baru</h3>
            <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route('instansi.store') }}" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Nama Instansi <span class="text-rose-500">*</span></label>
                <input type="text" name="nama_instansi" required placeholder="Contoh: PT. Maju Bersama" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Jenis Instansi</label>
                    <select name="jenis" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="eksternal">Eksternal</option>
                        <option value="internal">Internal</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Kontak Person</label>
                    <input type="text" name="kontak_person" placeholder="Nama PIC" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Telepon</label>
                    <input type="text" name="telepon" placeholder="08123456789" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Email</label>
                    <input type="email" name="email" placeholder="info@instansi.com" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Alamat</label>
                <textarea name="alamat" rows="2" placeholder="Alamat lengkap instansi..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
            </div>
            <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')" class="px-4 py-2 text-sm text-slate-600 hover:bg-slate-100 rounded-xl">Batal</button>
                <button type="submit" class="px-5 py-2 text-sm font-semibold bg-blue-600 text-white hover:bg-blue-700 rounded-xl">Simpan Data</button>
            </div>
        </form>
    </div>
</div>
@endsection