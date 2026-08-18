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
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SuratKeluarController extends Controller
{
    /**
     * Menampilkan daftar Surat Keluar dengan filter dan pagination.
     */
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

    /**
     * Form pembuatan Surat Keluar baru.
     */
    public function create()
    {
        $kategoris = KategoriSurat::orderBy('nama_kategori')->get();
        $instansis = Instansi::orderBy('nama_instansi')->get();
        $penandatangan = User::where('role', 'admin')->orderBy('name')->get();

        return view('surat_keluar.create', compact('kategoris', 'instansis', 'penandatangan'));
    }

    /**
     * Menyimpan Surat Keluar baru ke database.
     */
    public function store(SuratKeluarRequest $request)
    {
        $data = $request->validated();
        $kategori = KategoriSurat::findOrFail($data['kategori_surat_id']);
        
        $data['nomor_surat'] = SuratKeluar::generateNomorSurat($kategori->kode);
        $data['diterima_oleh'] = Auth::id();

        // Upload file lampiran jika ada
        if ($request->hasFile('lampiran_file')) {
            $data['lampiran_file'] = $request->file('lampiran_file')->store('lampiran/surat_keluar', 'public');
        }

        $surat = SuratKeluar::create($data);

        // Catat Log Aktivitas
        ActivityLog::catat('create', 'surat_keluar', "Membuat surat keluar {$surat->nomor_surat} - {$surat->perihal}");

        return redirect()
            ->route('surat-keluar.index')
            ->with('success', "Surat keluar berhasil dibuat dengan Nomor {$surat->nomor_surat}.");
    }

    /**
     * Menampilkan detail Surat Keluar.
     */
    public function show(SuratKeluar $suratKeluar)
    {
        $suratKeluar->load(['instansi', 'kategori', 'pembuat', 'penandatangan']);
        
        return view('surat_keluar.show', compact('suratKeluar'));
    }

    /**
     * Form edit Surat Keluar.
     */
    public function edit(SuratKeluar $suratKeluar)
    {
        $kategoris = KategoriSurat::orderBy('nama_kategori')->get();
        $instansis = Instansi::orderBy('nama_instansi')->get();
        $penandatangan = User::where('role', 'admin')->orderBy('name')->get();

        return view('surat_keluar.edit', compact('suratKeluar', 'kategoris', 'instansis', 'penandatangan'));
    }

    /**
     * Memperbarui data Surat Keluar.
     */
    public function update(SuratKeluarRequest $request, SuratKeluar $suratKeluar)
    {
        $data = $request->validated();

        // Ganti file lampiran lama jika mengunggah file baru
        if ($request->hasFile('lampiran_file')) {
            if ($suratKeluar->lampiran_file && Storage::disk('public')->exists($suratKeluar->lampiran_file)) {
                Storage::disk('public')->delete($suratKeluar->lampiran_file);
            }
            $data['lampiran_file'] = $request->file('lampiran_file')->store('lampiran/surat_keluar', 'public');
        }

        $suratMasukNomor = $suratKeluar->nomor_surat;
        $suratKeluar->update($data);

        // Catat Log Aktivitas
        ActivityLog::catat('update', 'surat_keluar', "Mengubah surat keluar {$suratMasukNomor}");

        return redirect()
            ->route('surat-keluar.index')
            ->with('success', "Surat keluar dengan Nomor {$suratMasukNomor} berhasil diperbarui.");
    }

    /**
     * Menghapus data Surat Keluar beserta fisik lampirannya.
     */
    public function destroy(SuratKeluar $suratKeluar)
    {
        $nomor = $suratKeluar->nomor_surat;

        // Hapus berkas lampiran fisik dari storage jika ada
        if ($suratKeluar->lampiran_file && Storage::disk('public')->exists($suratKeluar->lampiran_file)) {
            Storage::disk('public')->delete($suratKeluar->lampiran_file);
        }

        $suratKeluar->delete();
        
        // Catat Log Aktivitas
        ActivityLog::catat('delete', 'surat_keluar', "Menghapus surat keluar {$nomor}");

        return redirect()
            ->route('surat-keluar.index')
            ->with('success', "Surat keluar {$nomor} berhasil dihapus.");
    }

    /**
     * Stream/Preview File Lampiran Surat Keluar (Bypass Symlink / Docker Support).
     *
     * Mendukung pemanggilan via Model Binding maupun Path String secara dinamis.
     */
    public function previewLampiran(Request $request, string $idOrPath): BinaryFileResponse
    {
        $filePath = null;

        // Jika parameter berupa ID / Model Binding
        if (is_numeric($idOrPath)) {
            $suratKeluar = SuratKeluar::findOrFail($idOrPath);
            $filePath = $suratKeluar->lampiran_file;
        } else {
            // Jika parameter berupa direct file path string
            $filePath = urldecode($idOrPath);
        }

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            abort(404, 'File lampiran tidak ditemukan di server.');
        }

        return response()->file(Storage::disk('public')->path($filePath));
    }
}