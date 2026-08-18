<?php

namespace App\Http\Controllers;

use App\Http\Requests\SuratKeluarRequest;
use App\Models\ActivityLog;
use App\Models\Instansi;
use App\Models\KategoriSurat;
use App\Models\SuratKeluar;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SuratKeluarController extends Controller
{
    public function index(Request $request)
    {
        $suratKeluars = SuratKeluar::with(['instansi', 'kategori', 'pembuat'])
            ->filter($request->only(['search', 'kategori_id', 'instansi_id', 'status', 'dari_tanggal', 'sampai_tanggal']))
            ->latest('tanggal_surat')
            ->paginate(10)
            ->withQueryString();

        $kategoris = KategoriSurat::orderBy('nama_kategori')->get();
        $instansis = Instansi::orderBy('nama_instansi')->get();

        return view('surat_keluar.index', compact('suratKeluars', 'kategoris', 'instansis'));
    }

    public function create()
    {
        $kategoris = KategoriSurat::orderBy('nama_kategori')->get();
        $instansis = Instansi::orderBy('nama_instansi')->get();
        $penandatangan = User::where('role', 'admin')->orderBy('name')->get();

        return view('surat_keluar.create', compact('kategoris', 'instansis', 'penandatangan'));
    }

    public function store(SuratKeluarRequest $request)
    {
        $data = $request->validated();
        $kategori = KategoriSurat::findOrFail($data['kategori_surat_id']);
        $data['nomor_surat'] = SuratKeluar::generateNomorSurat($kategori->kode);
        $data['diterima_oleh'] = Auth::id();

        if ($request->hasFile('lampiran_file')) {
            $data['lampiran_file'] = $request->file('lampiran_file')->store('lampiran/surat_keluar', 'public');
        }

        $surat = SuratKeluar::create($data);
        ActivityLog::catat('create', 'surat_keluar', "Membuat surat keluar {$surat->nomor_surat} - {$surat->perihal}");

        return redirect()->route('surat-keluar.index')->with('success', 'Surat keluar berhasil dibuat dengan nomor ' . $surat->nomor_surat);
    }

    public function show(SuratKeluar $suratKeluar)
    {
        $suratKeluar->load(['instansi', 'kategori', 'pembuat', 'penandatangan']);
        return view('surat_keluar.show', compact('suratKeluar'));
    }

    public function edit(SuratKeluar $suratKeluar)
    {
        $kategoris = KategoriSurat::orderBy('nama_kategori')->get();
        $instansis = Instansi::orderBy('nama_instansi')->get();
        $penandatangan = User::where('role', 'admin')->orderBy('name')->get();

        return view('surat_keluar.edit', compact('suratKeluar', 'kategoris', 'instansis', 'penandatangan'));
    }

    public function update(SuratKeluarRequest $request, SuratKeluar $suratKeluar)
    {
        $data = $request->validated();

        if ($request->hasFile('lampiran_file')) {
            // Hapus lampiran lama jika ada
            if ($suratKeluar->lampiran_file && Storage::disk('public')->exists($suratKeluar->lampiran_file)) {
                Storage::disk('public')->delete($suratKeluar->lampiran_file);
            }
            $data['lampiran_file'] = $request->file('lampiran_file')->store('lampiran/surat_keluar', 'public');
        }

        $suratKeluar->update($data);
        ActivityLog::catat('update', 'surat_keluar', "Mengubah surat keluar {$suratKeluar->nomor_surat}");

        return redirect()->route('surat-keluar.index')->with('success', 'Surat keluar berhasil diperbarui.');
    }

    public function destroy(SuratKeluar $suratKeluar)
    {
        $nomor = $suratKeluar->nomor_surat;

        // Hapus berkas lampiran fisik dari storage jika ada
        if ($suratKeluar->lampiran_file && Storage::disk('public')->exists($suratKeluar->lampiran_file)) {
            Storage::disk('public')->delete($suratKeluar->lampiran_file);
        }

        $suratKeluar->delete();
        ActivityLog::catat('delete', 'surat_keluar', "Menghapus surat keluar {$nomor}");

        return back()->with('success', 'Surat keluar berhasil dihapus.');
    }

    /**
     * Preview / Stream File Lampiran Surat Keluar
     */
    public function previewLampiran(SuratKeluar $suratKeluar)
    {
        if (!$suratKeluar->lampiran_file || !Storage::disk('public')->exists($suratKeluar->lampiran_file)) {
            abort(404, 'File lampiran tidak ditemukan.');
        }

        return response()->file(Storage::disk('public')->path($suratKeluar->lampiran_file));
    }
}