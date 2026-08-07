@extends('layouts.app')
@section('title', 'Buat Surat Keluar')
@section('content')
<div class="bg-white rounded-xl shadow-sm p-6 max-w-3xl">
    <form method="POST" action="{{ route('surat-keluar.store') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div class="bg-blue-50 text-blue-700 text-sm px-4 py-2 rounded-lg">Nomor surat akan digenerate otomatis sesuai format: 001/KODE-KATEGORI/BULAN-ROMAWI/TAHUN</div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Tanggal Surat</label>
                <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat', date('Y-m-d')) }}" required class="w-full rounded-lg border-slate-300">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Instansi Tujuan</label>
                <select name="instansi_id" required class="w-full rounded-lg border-slate-300">
                    <option value="">Pilih instansi</option>
                    @foreach($instansis as $i)
                        <option value="{{ $i->id }}">{{ $i->nama_instansi }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Kategori Surat</label>
                <select name="kategori_surat_id" required class="w-full rounded-lg border-slate-300">
                    <option value="">Pilih kategori</option>
                    @foreach($kategoris as $k)
                        <option value="{{ $k->id }}">{{ $k->nama_kategori }} ({{ $k->kode }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Ditandatangani Oleh</label>
                <select name="ditandatangani_oleh" class="w-full rounded-lg border-slate-300">
                    <option value="">-</option>
                    @foreach($penandatangan as $p)
                        <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->jabatan }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Status</label>
                <select name="status" required class="w-full rounded-lg border-slate-300">
                    <option value="draft">Draft</option>
                    <option value="dikirim">Dikirim</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Perihal</label>
            <input type="text" name="perihal" value="{{ old('perihal') }}" required class="w-full rounded-lg border-slate-300">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Ringkasan / Isi Surat</label>
            <textarea name="ringkasan" rows="4" class="w-full rounded-lg border-slate-300">{{ old('ringkasan') }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Upload File Surat (PDF)</label>
            <input type="file" name="lampiran_file" class="w-full text-sm">
        </div>

        <div class="flex justify-end gap-2 pt-4 border-t">
            <a href="{{ route('surat-keluar.index') }}" class="px-4 py-2 text-sm rounded-lg bg-slate-100 hover:bg-slate-200">Batal</a>
            <button class="px-4 py-2 text-sm rounded-lg bg-brand-600 text-white hover:bg-brand-700">Simpan Surat Keluar</button>
        </div>
    </form>
</div>
@endsection
