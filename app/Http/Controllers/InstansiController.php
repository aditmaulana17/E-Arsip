<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Instansi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InstansiController extends Controller
{
    /**
     * Menampilkan daftar Instansi dengan fitur pencarian dan pagination.
     */
    public function index(Request $request): View
    {
        $instansis = Instansi::when($request->search, fn ($q, $v) => $q->where('nama_instansi', 'like', "%{$v}%"))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('instansi.index', compact('instansis'));
    }

    /**
     * Menyimpan data Instansi baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama_instansi' => ['required', 'string', 'max:150'],
            'jenis'         => ['required', 'in:internal,eksternal'],
            'alamat'        => ['nullable', 'string'],
            'telepon'       => ['nullable', 'string', 'max:30'],
            'email'         => ['nullable', 'email'],
            'kontak_person' => ['nullable', 'string', 'max:100'],
        ]);

        $instansi = Instansi::create($data);

        // Catat Log Aktivitas
        ActivityLog::catat('create', 'instansi', "Menambah instansi {$instansi->nama_instansi}");

        return redirect()
            ->route('instansi.index')
            ->with('success', "Instansi '{$instansi->nama_instansi}' berhasil ditambahkan.");
    }

    /**
     * Memperbarui data Instansi.
     */
    public function update(Request $request, Instansi $instansi): RedirectResponse
    {
        $data = $request->validate([
            'nama_instansi' => ['required', 'string', 'max:150'],
            'jenis'         => ['required', 'in:internal,eksternal'],
            'alamat'        => ['nullable', 'string'],
            'telepon'       => ['nullable', 'string', 'max:30'],
            'email'         => ['nullable', 'email'],
            'kontak_person' => ['nullable', 'string', 'max:100'],
        ]);

        $instansi->update($data);

        // Catat Log Aktivitas
        ActivityLog::catat('update', 'instansi', "Mengubah instansi {$instansi->nama_instansi}");

        return redirect()
            ->route('instansi.index')
            ->with('success', "Instansi '{$instansi->nama_instansi}' berhasil diperbarui.");
    }

    /**
     * Menghapus Instansi dengan validasi relasi data.
     */
    public function destroy(Instansi $instansi): RedirectResponse
    {
        // Proteksi: Cek apakah instansi masih terikat pada Surat Masuk atau Surat Keluar
        $suratMasukCount = $instansi->suratMasuk()->count();
        $suratKeluarCount = $instansi->suratKeluar()->count();

        if ($suratMasukCount > 0 || $suratKeluarCount > 0) {
            return back()->with('error', "Instansi '{$instansi->nama_instansi}' tidak dapat dihapus karena masih terikat dengan data surat.");
        }

        $nama = $instansi->nama_instansi;
        $instansi->delete();

        // Catat Log Aktivitas
        ActivityLog::catat('delete', 'instansi', "Menghapus instansi {$nama}");

        return redirect()
            ->route('instansi.index')
            ->with('success', "Instansi '{$nama}' berhasil dihapus.");
    }
}