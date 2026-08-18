@extends('layouts.app')

@section('title', 'Ubah Disposisi')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">

    <!-- HEADER -->
    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Ubah Disposisi</h1>
            <p class="text-sm text-slate-500 mt-0.5">Edit informasi dan instruksi disposisi surat masuk.</p>
        </div>
        <a href="{{ route('disposisi.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Kembali</span>
        </a>
    </div>

    <!-- FORM EDIT -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6">
        <form action="{{ route('disposisi.update', $disposisi) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Penerima Disposisi -->
            <div>
                <label for="user_id" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Penerima Disposisi</label>
                <select name="user_id" id="user_id" class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150 text-slate-700">
                    <option value="">Pilih Penerima</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" @selected(old('user_id', $disposisi->user_id ?? $disposisi->kepada_id) == $user->id)>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Sifat & Batas Waktu -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="sifat" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Sifat Disposisi</label>
                    <select name="sifat" id="sifat" class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150 text-slate-700">
                        @foreach(['biasa', 'penting', 'segera', 'rahasia'] as $sf)
                            <option value="{{ $sf }}" @selected(old('sifat', $disposisi->sifat) == $sf)>{{ ucfirst($sf) }}</option>
                        @endforeach
                    </select>
                    @error('sifat')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="batas_waktu" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Batas Waktu</label>
                    <input type="date" name="batas_waktu" id="batas_waktu" 
                        value="{{ old('batas_waktu', optional($disposisi->batas_waktu)->format('Y-m-d') ?? $disposisi->batas_waktu) }}"
                        class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150 text-slate-700">
                    @error('batas_waktu')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Isi Disposisi -->
            <div>
                <label for="isi_disposisi" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Isi Instruksi / Catatan</label>
                <textarea name="isi_disposisi" id="isi_disposisi" rows="4" 
                    class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150 text-slate-700" 
                    placeholder="Masukkan instruksi disposisi...">{{ old('isi_disposisi', $disposisi->isi_disposisi ?? $disposisi->catatan) }}</textarea>
                @error('isi_disposisi')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tombol Simpan -->
            <div class="flex justify-end gap-2 pt-3">
                <a href="{{ route('disposisi.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition">Batal</a>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold shadow-sm transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</div>
@endsection