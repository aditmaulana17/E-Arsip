<?php

namespace App\Http\Controllers;

use App\Http\Requests\SuratMasukRequest;
use App\Models\ActivityLog;
use App\Models\Instansi;
use App\Models\KategoriSurat;
use App\Models\SuratMasuk;
use Illuminate\Contracts\View\View;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SuratMasukController extends Controller
{
    /**
     * Menampilkan daftar Surat Masuk dengan filter dan pagination.
     */
    public function index(Request $request): View
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
    public function create(): View
    {
        $kategoris = KategoriSurat::orderBy('nama_kategori')->get();
        $instansis = Instansi::orderBy('nama_instansi')->get();
        $nomorAgenda = SuratMasuk::generateNomorAgenda();

        return view('surat_masuk.create', compact('kategoris', 'instansis', 'nomorAgenda'));
    }

    /**
     * Menyimpan Surat Masuk baru ke database.
     */
    public function store(SuratMasukRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['diterima_oleh'] = Auth::id();

        // Process file upload jika ada
        if ($request->hasFile('lampiran_file')) {
            $path = $this->handleFileUpload($request->file('lampiran_file'));
            $data['lampiran_file'] = $path;
            
            // Kompatibilitas dengan skema tabel lama/baru
            if (array_key_exists('lampiran', (new SuratMasuk())->getAttributes())) {
                $data['lampiran'] = $path;
            }
        }

        // Simpan data dengan Transaksi DB & Auto-Retry untuk nomor agenda unik
        try {
            $surat = DB::transaction(function () use ($data) {
                if (empty($data['nomor_agenda']) || SuratMasuk::withTrashed()->where('nomor_agenda', $data['nomor_agenda'])->exists()) {
                    $data['nomor_agenda'] = SuratMasuk::generateNomorAgenda();
                }
                return SuratMasuk::create($data);
            });
        } catch (UniqueConstraintViolationException $e) {
            $data['nomor_agenda'] = SuratMasuk::generateNomorAgenda();
            $surat = SuratMasuk::create($data);
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan Surat Masuk: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal menyimpan data surat masuk. Silakan coba lagi.');
        }

        ActivityLog::catat('create', 'surat_masuk', "Menambah surat masuk {$surat->nomor_agenda} - {$surat->perihal}");

        return redirect()
            ->route('surat-masuk.index')
            ->with('success', "Surat masuk berhasil dicatat dengan Nomor Agenda {$surat->nomor_agenda}.");
    }

    /**
     * Menampilkan detail Surat Masuk beserta Disposisi terikat.
     */
    public function show(SuratMasuk $suratMasuk): View
    {
        $suratMasuk->load(['instansi', 'kategori', 'penerima', 'disposisi.dari', 'disposisi.kepada']);
        
        return view('surat_masuk.show', compact('suratMasuk'));
    }

    /**
     * Form edit Surat Masuk.
     */
    public function edit(SuratMasuk $suratMasuk): View
    {
        $kategoris = KategoriSurat::orderBy('nama_kategori')->get();
        $instansis = Instansi::orderBy('nama_instansi')->get();

        return view('surat_masuk.edit', compact('suratMasuk', 'kategoris', 'instansis'));
    }

    /**
     * Memperbarui data Surat Masuk.
     */
    public function update(SuratMasukRequest $request, SuratMasuk $suratMasuk): RedirectResponse
    {
        $data = $request->validated();

        // Ganti file lampiran lama jika mengunggah file baru
        if ($request->hasFile('lampiran_file')) {
            $this->deleteOldFile($suratMasuk);
            
            $path = $this->handleFileUpload($request->file('lampiran_file'));
            $data['lampiran_file'] = $path;

            if (array_key_exists('lampiran', $suratMasuk->getAttributes())) {
                $data['lampiran'] = $path;
            }
        }

        try {
            $suratMasuk->update($data);
            ActivityLog::catat('update', 'surat_masuk', "Mengubah surat masuk {$suratMasuk->nomor_agenda}");
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui Surat Masuk: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal memperbarui data surat masuk.');
        }

        return redirect()
            ->route('surat-masuk.index')
            ->with('success', "Surat masuk dengan Nomor Agenda {$suratMasuk->nomor_agenda} berhasil diperbarui.");
    }

    /**
     * Menghapus (Soft Delete) data Surat Masuk.
     */
    public function destroy(SuratMasuk $suratMasuk): RedirectResponse
    {
        $nomorAgenda = $suratMasuk->nomor_agenda;
        
        try {
            $suratMasuk->delete();
            ActivityLog::catat('delete', 'surat_masuk', "Menghapus surat masuk {$nomorAgenda}");
        } catch (\Exception $e) {
            Log::error('Gagal menghapus Surat Masuk: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus surat masuk.');
        }

        return redirect()
            ->route('surat-masuk.index')
            ->with('success', "Surat masuk {$nomorAgenda} berhasil dihapus.");
    }

    /**
     * Mengunggah lampiran berkas / hasil scan kamera dari halaman Detail Modal.
     */
    public function uploadLampiran(Request $request, SuratMasuk $suratMasuk): RedirectResponse
    {
        $request->validate([
            'lampiran_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'lampiran_file.required' => 'Pilih berkas terlebih dahulu.',
            'lampiran_file.mimes'    => 'Format berkas harus berupa PDF, JPG, JPEG, atau PNG.',
            'lampiran_file.max'      => 'Ukuran berkas maksimal adalah 5MB.',
        ]);

        if ($request->hasFile('lampiran_file')) {
            $this->deleteOldFile($suratMasuk);

            $path = $this->handleFileUpload($request->file('lampiran_file'));

            $updateData = ['lampiran_file' => $path];
            if (array_key_exists('lampiran', $suratMasuk->getAttributes())) {
                $updateData['lampiran'] = $path;
            }

            $suratMasuk->update($updateData);

            ActivityLog::catat('update', 'surat_masuk', "Mengunggah lampiran surat masuk {$suratMasuk->nomor_agenda}");
        }

        return back()->with('success', 'Lampiran berkas berhasil diunggah.');
    }

    /**
     * Mencetak label agenda Surat Masuk.
     */
    public function cetakLabel(SuratMasuk $suratMasuk): View
    {
        $suratMasuk->load(['instansi', 'kategori']);
        
        return view('surat_masuk.label', compact('suratMasuk'));
    }

    /**
     * Preview lampiran dokumen (PDF / Gambar) langsung di browser.
     */
    public function previewLampiran(SuratMasuk $suratMasuk): BinaryFileResponse
    {
        $fileRelativePath = $suratMasuk->lampiran_file ?? $suratMasuk->lampiran;

        if (!$fileRelativePath || !Storage::disk('public')->exists($fileRelativePath)) {
            abort(404, 'File lampiran tidak ditemukan di server.');
        }

        $filePath = Storage::disk('public')->path($fileRelativePath);
        $mimeType = mime_content_type($filePath) ?: 'application/octet-stream';

        return response()->file($filePath, [
            'Content-Type'        => $mimeType,
            'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"'
        ]);
    }

    /**
     * Mengunduh lampiran secara langsung (Direct Download).
     */
    public function downloadLampiran(SuratMasuk $suratMasuk): BinaryFileResponse
    {
        $fileRelativePath = $suratMasuk->lampiran_file ?? $suratMasuk->lampiran;

        if (!$fileRelativePath || !Storage::disk('public')->exists($fileRelativePath)) {
            abort(404, 'File lampiran tidak ditemukan untuk diunduh.');
        }

        $filePath = Storage::disk('public')->path($fileRelativePath);

        return response()->download($filePath, basename($filePath));
    }

    /**
     * Cetak Lembar Disposisi Surat Masuk.
     */
    public function cetakDisposisi(SuratMasuk $suratMasuk): View
    {
        $suratMasuk->load(['instansi', 'kategori', 'penerima', 'disposisi.dari', 'disposisi.kepada']);

        return view('surat_masuk.disposisi_pdf', compact('suratMasuk'));
    }

    /**
     * Helper privat untuk penanganan upload file lampiran.
     */
    private function handleFileUpload($file): string
    {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $safeName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $originalName);
        $filename = 'surat_' . time() . '_' . $safeName . '.' . $extension;

        return $file->storeAs('lampiran/surat_masuk', $filename, 'public');
    }

    /**
     * Helper privat untuk menghapus berkas lama jika tersedia.
     */
    private function deleteOldFile(SuratMasuk $suratMasuk): void
    {
        $oldPath = $suratMasuk->lampiran_file ?? $suratMasuk->lampiran;
        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }
    }
}