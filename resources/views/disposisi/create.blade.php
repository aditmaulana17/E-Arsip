@extends('layouts.app')
@section('title', 'Buat Disposisi')
@section('content')
<div class="bg-white rounded-xl shadow-sm p-6 max-w-2xl">
    <p class="text-sm text-slate-500 mb-4">Surat: <strong>{{ $suratMasuk->nomor_agenda }} - {{ $suratMasuk->perihal }}</strong></p>
    <form method="POST" action="{{ route('disposisi.store') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="surat_masuk_id" value="{{ $suratMasuk->id }}">
        <div>
            <label class="block text-sm font-medium mb-1">Disposisikan Kepada</label>
            <select name="kepada_user_id" required class="w-full rounded-lg border-slate-300">
                <option value="">Pilih pengguna</option>
                @foreach($users as $u)
                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->jabatan }})</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Instruksi</label>
            <textarea name="instruksi" rows="2" required placeholder="Contoh: Mohon ditindaklanjuti segera" class="w-full rounded-lg border-slate-300"></textarea>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Catatan Tambahan</label>
            <textarea name="catatan" rows="2" class="w-full rounded-lg border-slate-300"></textarea>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Batas Waktu</label>
                <input type="date" name="batas_waktu" class="w-full rounded-lg border-slate-300">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Status</label>
                <select name="status" required class="w-full rounded-lg border-slate-300">
                    <option value="menunggu">Menunggu</option>
                </select>
            </div>
        </div>
        <div class="flex justify-end gap-2 pt-4 border-t">
            <a href="{{ route('surat-masuk.show', $suratMasuk) }}" class="px-4 py-2 text-sm rounded-lg bg-slate-100 hover:bg-slate-200">Batal</a>
            <button class="px-4 py-2 text-sm rounded-lg bg-brand-600 text-white hover:bg-brand-700">Kirim Disposisi</button>
        </div>
    </form>
</div>
@endsection
