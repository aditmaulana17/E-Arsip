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
    // ... (method index, create tetap sama) ...

    /**
     * Menyimpan Surat Masuk baru ke database.
     */
    public function store(SuratMasukRequest $request)
    {
        $data = $request->validated();
        $data['nomor_agenda'] = SuratMasuk::generateNomorAgenda();
        $data['diterima_oleh'] = Auth::id();

        // Upload file lampiran (PDF atau Gambar Hasil Scan)
        if ($request->hasFile('lampiran_file')) {
            // Menggunakan storeAs agar nama file lebih unik dan rapi
            $file = $request->file('lampiran_file');
            $filename = 'surat_' . time() . '_' . $file->getClientOriginalName();
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
     * Memperbarui data Surat Masuk.
     */
    public function update(SuratMasukRequest $request, SuratMasuk $suratMasuk)
    {
        $data = $request->validated();

        // Ganti file lampiran lama jika mengunggah file baru
        if ($request->hasFile('lampiran_file')) {
            // Hapus file lama jika ada
            if ($suratMasuk->lampiran_file && Storage::disk('public')->exists($suratMasuk->lampiran_file)) {
                Storage::disk('public')->delete($suratMasuk->lampiran_file);
            }
            
            // Simpan file baru dengan nama unik
            $file = $request->file('lampiran_file');
            $filename = 'surat_' . time() . '_' . $file->getClientOriginalName();
            $data['lampiran_file'] = $file->storeAs('lampiran/surat_masuk', $filename, 'public');
        }

        $suratMasuk->update($data);

        // Catat Log Aktivitas
        ActivityLog::catat('update', 'surat_masuk', "Mengubah surat masuk {$suratMasuk->nomor_agenda}");

        return redirect()
            ->route('surat-masuk.index')
            ->with('success', "Surat masuk dengan Nomor Agenda {$suratMasuk->nomor_agenda} berhasil diperbarui.");
    }

    // ... (method destroy, cetakLabel, previewLampiran, cetakDisposisi tetap sama) ...
}