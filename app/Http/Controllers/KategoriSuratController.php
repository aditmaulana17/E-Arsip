<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\KategoriSurat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KategoriSuratController extends Controller
{
    /**
     * Menampilkan daftar Kategori Surat beserta jumlah relasi suratnya.
     */
    public function index(): View
    {
        $kategoris = KategoriSurat::withCount(['suratMasuk', 'suratKeluar'])
            ->latest()
            ->paginate(10);

        return view('kategori.index', compact('kategoris'));
    }

    /**
     * Menyimpan data Kategori Surat baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:100'],
            'kode'          => ['required', 'string', 'max:20', 'unique:kategori_surats,kode'],
            'sifat'         => ['required', 'in:biasa,penting,rahasia'],
            'keterangan'    => ['nullable', 'string'],
        ]);

        $kategori = KategoriSurat::create($data);

        // Catat Log Aktivitas
        ActivityLog::catat('create', 'kategori', "Menambah kategori {$kategori->nama_kategori}");

        return redirect()
            ->route('kategori.index')
            ->with('success', "Kategori surat '{$kategori->nama_kategori}' berhasil ditambahkan.");
    }

    /**
     * Memperbarui data Kategori Surat.
     */
    public function update(Request $request, KategoriSurat $kategori): RedirectResponse
    {
        $data = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:100'],
            'kode'          => ['required', 'string', 'max:20', 'unique:kategori_surats,kode,' . $kategori->id],
            'sifat'         => ['required', 'in:biasa,penting,rahasia'],
            'keterangan'    => ['nullable', 'string'],
        ]);

        $kategori->update($data);

        // Catat Log Aktivitas
        ActivityLog::catat('update', 'kategori', "Mengubah kategori {$kategori->nama_kategori}");

        return redirect()
            ->route('kategori.index')
            ->with('success', "Kategori surat '{$kategori->nama_kategori}' berhasil diperbarui.");
    }

    /**
     * Menghapus Kategori Surat dengan validasi relasi data.
     */
    public function destroy(KategoriSurat $kategori): RedirectResponse
    {
        // Proteksi: Cek apakah kategori masih digunakan pada Surat Masuk atau Surat Keluar
        $suratMasukCount = $kategori->suratMasuk()->count();
        $suratKeluarCount = $kategori->suratKeluar()->count();

        if ($suratMasukCount > 0 || $suratKeluarCount > 0) {
            return back()->with('error', "Kategori '{$kategori->nama_kategori}' tidak dapat dihapus karena masih digunakan oleh data surat.");
        }

        $nama = $kategori->nama_kategori;
        $kategori->delete();

        // Catat Log Aktivitas
        ActivityLog::catat('delete', 'kategori', "Menghapus kategori {$nama}");

        return redirect()
            ->route('kategori.index')
            ->with('success', "Kategori surat '{$nama}' berhasil dihapus.");
    }
}