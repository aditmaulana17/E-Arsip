@extends('layouts.app')
@section('title', 'Ubah Surat Keluar')
@section('content')
<div class="bg-white rounded-xl shadow-sm p-6 max-w-3xl">
    <form method="POST" action="{{ route('surat-keluar.update', $suratKeluar) }}" enctype="multipart/form-data" class="space-y-4">
        @csrf @method('PUT')
        <div class="bg-slate-50 text-slate-600 text-sm px-4 py-2 rounded-lg">Nomor surat: <strong>{{ $suratKeluar->nomor_surat }}</strong></div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Tanggal Surat</label>
                <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat', $suratKeluar->tanggal_surat->format('Y-m-d')) }}" required class="w-full rounded-lg border-slate-300">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Instansi Tujuan</label>
                <select name="instansi_id" required class="w-full rounded-lg border-slate-300">
                    @foreach($instansis as $i)
                        <option value="{{ $i->id }}" @selected($suratKeluar->instansi_id == $i->id)>{{ $i->nama_instansi }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Kategori Surat</label>
                <select name="kategori_surat_id" required class="w-full rounded-lg border-slate-300">
                    @foreach($kategoris as $k)
                        <option value="{{ $k->id }}" @selected($suratKeluar->kategori_surat_id == $k->id)>{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Ditandatangani Oleh</label>
                <select name="ditandatangani_oleh" class="w-full rounded-lg border-slate-300">
                    <option value="">-</option>
                    @foreach($penandatangan as $p)
                        <option value="{{ $p->id }}" @selected($suratKeluar->ditandatangani_oleh == $p->id)>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Status</label>
                <select name="status" required class="w-full rounded-lg border-slate-300">
                    @foreach(['draft','dikirim','diarsipkan'] as $st)
                        <option value="{{ $st }}" @selected($suratKeluar->status == $st)>{{ ucfirst($st) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Perihal</label>
            <input type="text" name="perihal" value="{{ old('perihal', $suratKeluar->perihal) }}" required class="w-full rounded-lg border-slate-300">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Ringkasan</label>
            <textarea name="ringkasan" rows="4" class="w-full rounded-lg border-slate-300">{{ old('ringkasan', $suratKeluar->ringkasan) }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Ganti File Surat</label>
            @if($suratKeluar->lampiran_file)
                <a href="{{ \Illuminate\Support\Facades\Storage::url($suratKeluar->lampiran_file) }}" target="_blank" class="text-xs text-blue-600 underline block mb-1">Lihat file saat ini</a>
            @endif
            <input type="file" name="lampiran_file" class="w-full text-sm">
        </div>

        <div class="flex justify-end gap-2 pt-4 border-t">
            <a href="{{ route('surat-keluar.index') }}" class="px-4 py-2 text-sm rounded-lg bg-slate-100 hover:bg-slate-200">Batal</a>
            <button class="px-4 py-2 text-sm rounded-lg bg-brand-600 text-white hover:bg-brand-700">Perbarui</button>
        </div>
    </form>
</div>
@endsection
