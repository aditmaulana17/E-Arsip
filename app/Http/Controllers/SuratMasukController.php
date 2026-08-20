<?php

namespace App\Http\Controllers;

use App\Http\Requests\SuratMasukRequest;
use App\Models\ActivityLog;
use App\Models\Instansi;
use App\Models\KategoriSurat;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SuratMasukController extends Controller
{
    /**
     * Menampilkan daftar Surat Masuk dengan filter dan pagination.
     */
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

    /**
     * Form pembuatan Surat Masuk baru.
     */
    public function create()
    {
        $kategoris = KategoriSurat::orderBy('nama_kategori')->get();
        $instansis = Instansi::orderBy('nama_instansi')->get();
        $nomorAgenda = SuratMasuk::generateNomorAgenda();

        return view('surat_masuk.create', compact('kategoris', 'instansis', 'nomorAgenda'));
    }

    /**
     * Menyimpan Surat Masuk baru ke database.
     */
    public function store(SuratMasukRequest $request)
    {
        $data = $request->validated();
        $data['nomor_agenda'] = SuratMasuk::generateNomorAgenda();
        $data['diterima_oleh'] = Auth::id();

        // Upload file lampiran jika ada
        if ($request->hasFile('lampiran_file')) {
            $file = $request->file('lampiran_file');
            $filename = 'surat_' . time() . '_' . preg_replace('/[^A-Za-z0-9\.\-_]/', '_', $file->getClientOriginalName());
            $data['lampiran_file'] = $file->storeAs('lampiran/surat_masuk', $filename, 'public');
        }

        $surat = SuratMasuk::create($data);

        // Catat Log Aktivitas
        ActivityLog::catat('create', 'surat_masuk', "Menambah surat masuk {$surat->nomor_agenda} - {$surat->perihal}");

        return redirect()
            ->route('surat-masuk.index')
            ->with('success', "Surat masuk berhasil dicatat dengan Nomor Agenda {$surat->nomor_agenda}.");
    }

    /**
     * Menampilkan detail Surat Masuk beserta Disposisi terikat.
     */
    public function show(SuratMasuk $suratMasuk)
    {
        $suratMasuk->load(['instansi', 'kategori', 'penerima', 'disposisi.dari', 'disposisi.kepada']);
        
        return view('surat_masuk.show', compact('suratMasuk'));
    }

    /**
     * Form edit Surat Masuk.
     */
    public function edit(SuratMasuk $suratMasuk)
    {
        $kategoris = KategoriSurat::orderBy('nama_kategori')->get();
        $instansis = Instansi::orderBy('nama_instansi')->get();

        return view('surat_masuk.edit', compact('suratMasuk', 'kategoris', 'instansis'));
    }

    /**
     * Memperbarui data Surat Masuk.
     */
    public function update(SuratMasukRequest $request, SuratMasuk $suratMasuk)
    {
        $data = $request->validated();

        // Ganti file lampiran lama jika mengunggah file baru
        if ($request->hasFile('lampiran_file')) {
            if ($suratMasuk->lampiran_file && Storage::disk('public')->exists($suratMasuk->lampiran_file)) {
                Storage::disk('public')->delete($suratMasuk->lampiran_file);
            }
            $file = $request->file('lampiran_file');
            $filename = 'surat_' . time() . '_' . preg_replace('/[^A-Za-z0-9\.\-_]/', '_', $file->getClientOriginalName());
            $data['lampiran_file'] = $file->storeAs('lampiran/surat_masuk', $filename, 'public');
        }

        $suratMasuk->update($data);

        // Catat Log Aktivitas
        ActivityLog::catat('update', 'surat_masuk', "Mengubah surat masuk {$suratMasuk->nomor_agenda}");

        return redirect()
            ->route('surat-masuk.index')
            ->with('success', "Surat masuk dengan Nomor Agenda {$suratMasuk->nomor_agenda} berhasil diperbarui.");
    }

    /**
     * Menghapus (Soft Delete) data Surat Masuk.
     */
    public function destroy(SuratMasuk $suratMasuk)
    {
        $nomorAgenda = $suratMasuk->nomor_agenda;
        
        $suratMasuk->delete();
        
        ActivityLog::catat('delete', 'surat_masuk', "Menghapus surat masuk {$nomorAgenda}");

        return redirect()
            ->route('surat-masuk.index')
            ->with('success', "Surat masuk {$nomorAgenda} berhasil dihapus.");
    }

    /**
     * Mencetak label agenda Surat Masuk.
     */
    public function cetakLabel(SuratMasuk $suratMasuk)
    {
        $suratMasuk->load(['instansi', 'kategori']);
        
        return view('surat_masuk.label', compact('suratMasuk'));
    }

    /**
     * Stream/Preview file lampiran secara fleksibel (Support PDF, PNG, JPG, WebP).
   public function previewLampiran(SuratMasuk $suratMasuk): BinaryFileResponse
    {
        // Fleksibilitas pengecekan nama kolom
        $fileRelativePath = $suratMasuk->lampiran_file ?? $suratMasuk->lampiran;

        if (!$fileRelativePath || !Storage::disk('public')->exists($fileRelativePath)) {
            abort(404, 'File lampiran tidak ditemukan di server.');
        }

        $filePath = Storage::disk('public')->path($fileRelativePath);
        
        // Menggunakan helper native PHP untuk menghindari error/warning pada VS Code Intelephense
        $mimeType = mime_content_type($filePath) ?: 'application/octet-stream';

        return response()->file($filePath, [
            'Content-Type'        => $mimeType,
            'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"'
        ]);
    }

    /**
     * Cetak Lembar Disposisi Surat Masuk.
     */
    public function cetakDisposisi(SuratMasuk $suratMasuk)
    {
        $suratMasuk->load(['instansi', 'kategori', 'penerima', 'disposisi.dari', 'disposisi.kepada']);

        return view('surat_masuk.disposisi_pdf', compact('suratMasuk'));
    }
}