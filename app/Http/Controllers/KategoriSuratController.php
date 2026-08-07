<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\KategoriSurat;
use Illuminate\Http\Request;

class KategoriSuratController extends Controller
{
    public function index()
    {
        $kategoris = KategoriSurat::withCount(['suratMasuk', 'suratKeluar'])->latest()->paginate(10);
        return view('kategori.index', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:100'],
            'kode' => ['required', 'string', 'max:20', 'unique:kategori_surats,kode'],
            'sifat' => ['required', 'in:biasa,penting,rahasia'],
            'keterangan' => ['nullable', 'string'],
        ]);

        KategoriSurat::create($data);
        ActivityLog::catat('create', 'kategori', "Menambah kategori {$data['nama_kategori']}");

        return back()->with('success', 'Kategori surat berhasil ditambahkan.');
    }

    public function update(Request $request, KategoriSurat $kategori)
    {
        $data = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:100'],
            'kode' => ['required', 'string', 'max:20', 'unique:kategori_surats,kode,' . $kategori->id],
            'sifat' => ['required', 'in:biasa,penting,rahasia'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $kategori->update($data);
        ActivityLog::catat('update', 'kategori', "Mengubah kategori {$kategori->nama_kategori}");

        return back()->with('success', 'Kategori surat berhasil diperbarui.');
    }

    public function destroy(KategoriSurat $kategori)
    {
        $nama = $kategori->nama_kategori;
        $kategori->delete();
        ActivityLog::catat('delete', 'kategori', "Menghapus kategori {$nama}");

        return back()->with('success', 'Kategori surat berhasil dihapus.');
    }
}
