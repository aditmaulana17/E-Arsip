<?php

namespace App\Http\Controllers;

use App\Http\Requests\SuratMasukRequest;
use App\Models\ActivityLog;
use App\Models\Instansi;
use App\Models\KategoriSurat;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuratMasukController extends Controller
{
    public function index(Request $request)
    {
        $suratMasuks = SuratMasuk::with(['instansi', 'kategori', 'penerima'])
            ->filter($request->only(['search', 'kategori_id', 'instansi_id', 'status', 'dari_tanggal', 'sampai_tanggal']))
            ->latest('tanggal_terima')
            ->paginate(10)
            ->withQueryString();

        $kategoris = KategoriSurat::orderBy('nama_kategori')->get();
        $instansis = Instansi::orderBy('nama_instansi')->get();

        return view('surat_masuk.index', compact('suratMasuks', 'kategoris', 'instansis'));
    }

    public function create()
    {
        $kategoris = KategoriSurat::orderBy('nama_kategori')->get();
        $instansis = Instansi::orderBy('nama_instansi')->get();
        $nomorAgenda = SuratMasuk::generateNomorAgenda();

        return view('surat_masuk.create', compact('kategoris', 'instansis', 'nomorAgenda'));
    }

    public function store(SuratMasukRequest $request)
    {
        $data = $request->validated();
        $data['nomor_agenda'] = SuratMasuk::generateNomorAgenda();
        $data['diterima_oleh'] = auth()->id();

        if ($request->hasFile('lampiran_file')) {
            $data['lampiran_file'] = $request->file('lampiran_file')->store('lampiran/surat_masuk', 'public');
        }

        $surat = SuratMasuk::create($data);
        ActivityLog::catat('create', 'surat_masuk', "Menambah surat masuk {$surat->nomor_agenda} - {$surat->perihal}");

        return redirect()->route('surat-masuk.index')->with('success', 'Surat masuk berhasil dicatat dengan nomor agenda ' . $surat->nomor_agenda);
    }

    public function show(SuratMasuk $suratMasuk)
    {
        $suratMasuk->load(['instansi', 'kategori', 'penerima', 'disposisi.dari', 'disposisi.kepada']);
        return view('surat_masuk.show', compact('suratMasuk'));
    }

    public function edit(SuratMasuk $suratMasuk)
    {
        $kategoris = KategoriSurat::orderBy('nama_kategori')->get();
        $instansis = Instansi::orderBy('nama_instansi')->get();

        return view('surat_masuk.edit', compact('suratMasuk', 'kategoris', 'instansis'));
    }

    public function update(SuratMasukRequest $request, SuratMasuk $suratMasuk)
    {
        $data = $request->validated();

        if ($request->hasFile('lampiran_file')) {
            if ($suratMasuk->lampiran_file) {
                Storage::disk('public')->delete($suratMasuk->lampiran_file);
            }
            $data['lampiran_file'] = $request->file('lampiran_file')->store('lampiran/surat_masuk', 'public');
        }

        $suratMasuk->update($data);
        ActivityLog::catat('update', 'surat_masuk', "Mengubah surat masuk {$suratMasuk->nomor_agenda}");

        return redirect()->route('surat-masuk.index')->with('success', 'Surat masuk berhasil diperbarui.');
    }

    public function destroy(SuratMasuk $suratMasuk)
    {
        $nomor = $suratMasuk->nomor_agenda;
        $suratMasuk->delete(); // soft delete
        ActivityLog::catat('delete', 'surat_masuk', "Menghapus surat masuk {$nomor}");

        return back()->with('success', 'Surat masuk berhasil dihapus (dipindahkan ke arsip terhapus).');
    }

    public function cetakLabel(SuratMasuk $suratMasuk)
    {
        $suratMasuk->load(['instansi', 'kategori']);
        return view('surat_masuk.label', compact('suratMasuk'));
    }
}
