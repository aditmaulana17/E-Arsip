@extends('layouts.app')
@section('title', 'Ubah Surat Masuk')
@section('content')
<div class="bg-white rounded-xl shadow-sm p-6 max-w-3xl">
    <form method="POST" action="{{ route('surat-masuk.update', $suratMasuk) }}" enctype="multipart/form-data" class="space-y-4">
        @csrf @method('PUT')
        <div class="bg-slate-50 text-slate-600 text-sm px-4 py-2 rounded-lg">Nomor agenda: <strong>{{ $suratMasuk->nomor_agenda }}</strong></div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Nomor Surat</label>
                <input type="text" name="nomor_surat" value="{{ old('nomor_surat', $suratMasuk->nomor_surat) }}" required class="w-full rounded-lg border-slate-300">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Instansi Pengirim</label>
                <select name="instansi_id" required class="w-full rounded-lg border-slate-300">
                    @foreach($instansis as $i)
                        <option value="{{ $i->id }}" @selected($suratMasuk->instansi_id == $i->id)>{{ $i->nama_instansi }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Tanggal Surat</label>
                <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat', $suratMasuk->tanggal_surat->format('Y-m-d')) }}" required class="w-full rounded-lg border-slate-300">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Tanggal Diterima</label>
                <input type="date" name="tanggal_terima" value="{{ old('tanggal_terima', $suratMasuk->tanggal_terima->format('Y-m-d')) }}" required class="w-full rounded-lg border-slate-300">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Kategori Surat</label>
                <select name="kategori_surat_id" required class="w-full rounded-lg border-slate-300">
                    @foreach($kategoris as $k)
                        <option value="{{ $k->id }}" @selected($suratMasuk->kategori_surat_id == $k->id)>{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Status</label>
                <select name="status" required class="w-full rounded-lg border-slate-300">
                    @foreach(['baru','diproses','didisposisikan','selesai','diarsipkan'] as $st)
                        <option value="{{ $st }}" @selected($suratMasuk->status == $st)>{{ ucfirst($st) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Perihal</label>
            <input type="text" name="perihal" value="{{ old('perihal', $suratMasuk->perihal) }}" required class="w-full rounded-lg border-slate-300">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Ringkasan Isi Surat</label>
            <textarea name="ringkasan" rows="3" class="w-full rounded-lg border-slate-300">{{ old('ringkasan', $suratMasuk->ringkasan) }}</textarea>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Ganti Scan/Lampiran</label>
                @if($suratMasuk->lampiran_file)
                    <a href="{{ \Illuminate\Support\Facades\Storage::url($suratMasuk->lampiran_file) }}" target="_blank" class="text-xs text-blue-600 underline block mb-1">Lihat lampiran saat ini</a>
                @endif
                <input type="file" name="lampiran_file" class="w-full text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Lokasi Arsip Fisik</label>
                <input type="text" name="lokasi_arsip_fisik" value="{{ old('lokasi_arsip_fisik', $suratMasuk->lokasi_arsip_fisik) }}" class="w-full rounded-lg border-slate-300">
            </div>
        </div>

        <div class="flex justify-end gap-2 pt-4 border-t">
            <a href="{{ route('surat-masuk.index') }}" class="px-4 py-2 text-sm rounded-lg bg-slate-100 hover:bg-slate-200">Batal</a>
            <button class="px-4 py-2 text-sm rounded-lg bg-brand-600 text-white hover:bg-brand-700">Perbarui</button>
        </div>
    </form>
</div>
@endsection
