@extends('layouts.app')
@section('title', 'Manajemen Pengguna')

@section('content')
<div class="space-y-6">

    <!-- HEADER & TOMBOL TAMBAH -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Manajemen Pengguna</h1>
            <p class="text-sm text-slate-500">Kelola akun pengguna, peran, serta hak akses sistem.</p>
        </div>

        <button type="button" 
                onclick="document.getElementById('modalTambah').classList.remove('hidden')" 
                class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold bg-brand-600 text-white hover:bg-brand-700 rounded-xl transition-all shadow-sm hover:shadow self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Pengguna
        </button>
    </div>

    <!-- FILTER & SEARCH SECTION -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200/80 p-4">
        <form method="GET" class="flex flex-col sm:flex-row items-center gap-3">
            <div class="relative w-full sm:w-80">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari nama atau email..." 
                       class="w-full rounded-lg border-slate-300 text-sm focus:ring-brand-500 focus:border-brand-500 pl-9">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-slate-800 text-white rounded-lg text-sm font-medium hover:bg-slate-900 transition-colors">
                Cari
            </button>

            @if(request()->filled('search'))
                <a href="{{ route('users.index') }}" class="text-sm text-slate-500 hover:text-slate-700 underline px-2">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- TABEL DATA -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-600 font-semibold uppercase text-xs tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-3.5">Pengguna</th>
                        <th class="px-4 py-3.5">Role</th>
                        <th class="px-4 py-3.5">Jabatan</th>
                        <th class="px-4 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($users as $u)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <!-- Nama & Email dengan Avatar -->
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-brand-100 text-brand-700 font-bold flex items-center justify-center text-xs shadow-inner">
                                    {{ strtoupper(substr($u->name ?? $u->nama, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-slate-800">{{ $u->name ?? $u->nama }}</div>
                                    <div class="text-xs text-slate-400">{{ $u->email }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- Role -->
                        <td class="px-4 py-3.5 whitespace-nowrap">
                            @php
                                $role = strtolower($u->role ?? 'staff');
                                $roleClass = match($role) {
                                    'admin', 'administrator' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'pimpinan'               => 'bg-purple-50 text-purple-700 border-purple-200',
                                    default                  => 'bg-slate-100 text-slate-700 border-slate-200'
                                };
                            @endphp
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full border {{ $roleClass }}">
                                {{ ucfirst($u->role ?? 'Staff') }}
                            </span>
                        </td>

                        <!-- Jabatan -->
                        <td class="px-4 py-3.5 text-slate-700 whitespace-nowrap">
                            {{ $u->jabatan ?? '-' }}
                        </td>

                        <!-- Status -->
                        <td class="px-4 py-3.5 whitespace-nowrap">
                            @php
                                $status = strtolower($u->status ?? 'aktif');
                                $statusClass = ($status == 'aktif') 
                                    ? 'bg-emerald-50 text-emerald-700 border-emerald-200' 
                                    : 'bg-rose-50 text-rose-700 border-rose-200';
                            @endphp
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full border {{ $statusClass }}">
                                {{ ucfirst($status) }}
                            </span>
                        </td>

                        <!-- Aksi -->
                        <td class="px-5 py-3.5 text-right whitespace-nowrap">
                            <div class="inline-flex items-center gap-1.5">
                                <button type="button" 
                                        onclick="document.getElementById('modalEdit{{ $u->id }}').classList.remove('hidden')" 
                                        class="p-1.5 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" 
                                        title="Ubah Pengguna">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>

                                @if(auth()->id() !== $u->id)
                                <form action="{{ route('users.destroy', $u) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus Pengguna">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-12">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                                <p class="text-slate-600 font-medium">Belum ada data pengguna</p>
                                <p class="text-slate-400 text-xs mt-1">Silakan tambahkan data pengguna baru.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- PAGINATION -->
    @if(isset($users) && method_exists($users, 'links'))
    <div class="pt-2">
        {{ $users->links() }}
    </div>
    @endif

</div>

<!-- ========================================== -->
<!-- MODAL EDIT (LOOP DI LUAR TABEL) -->
<!-- ========================================== -->
@foreach($users as $u)
<div id="modalEdit{{ $u->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4 overflow-y-auto">
    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden transform transition-all">
        
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-slate-50/50">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-amber-50 text-amber-600 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800">Ubah Data Pengguna</h3>
                    <p class="text-xs text-slate-500">Perbarui informasi akun pengguna ini.</p>
                </div>
            </div>
            <button type="button" onclick="document.getElementById('modalEdit{{ $u->id }}').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Form Edit -->
        <form method="POST" action="{{ route('users.update', $u) }}">
            @csrf @method('PUT')
            
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ $u->name ?? $u->nama }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-800 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Email <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" value="{{ $u->email }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-800 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Kata Sandi Baru <span class="text-slate-400 font-normal lowercase">(kosongkan jika tidak diubah)</span></label>
                        <input type="password" name="password" placeholder="••••••••" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-800 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Role / Peran <span class="text-rose-500">*</span></label>
                        <select name="role" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-800 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                            <option value="admin" @selected(strtolower($u->role ?? '') == 'admin')>Admin</option>
                            <option value="staff" @selected(strtolower($u->role ?? '') == 'staff')>Staff</option>
                            <option value="pimpinan" @selected(strtolower($u->role ?? '') == 'pimpinan')>Pimpinan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Jabatan <span class="text-slate-400 font-normal lowercase">(opsional)</span></label>
                        <input type="text" name="jabatan" value="{{ $u->jabatan }}" placeholder="Kepala Tata Usaha, Staff Admin" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-800 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Status Akun <span class="text-rose-500">*</span></label>
                    <select name="status" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-800 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                        <option value="aktif" @selected(strtolower($u->status ?? '') == 'aktif')>Aktif</option>
                        <option value="nonaktif" @selected(strtolower($u->status ?? '') == 'nonaktif')>Nonaktif</option>
                    </select>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end gap-2 px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                <button type="button" onclick="document.getElementById('modalEdit{{ $u->id }}').classList.add('hidden')" class="px-4 py-2.5 text-sm font-medium text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-colors">Batal</button>
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
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800">Tambah Pengguna Baru</h3>
                    <p class="text-xs text-slate-500">Isi formulir untuk menambahkan akun pengguna baru.</p>
                </div>
            </div>
            <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Form Tambah -->
        <form method="POST" action="{{ route('users.store') }}">
            @csrf
            
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" placeholder="Contoh: Ahmad Subagja" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-800 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Email <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" placeholder="nama@arsipsurat.com" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-800 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Kata Sandi <span class="text-rose-500">*</span></label>
                        <input type="password" name="password" placeholder="••••••••" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-800 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Role / Peran <span class="text-rose-500">*</span></label>
                        <select name="role" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-800 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                            <option value="staff">Staff</option>
                            <option value="admin">Admin</option>
                            <option value="pimpinan">Pimpinan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Jabatan <span class="text-slate-400 font-normal lowercase">(opsional)</span></label>
                        <input type="text" name="jabatan" placeholder="Contoh: Staff Administrasi" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-800 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                    </div>
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