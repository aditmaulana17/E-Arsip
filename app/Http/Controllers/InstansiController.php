<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Instansi;
use Illuminate\Http\Request;

class InstansiController extends Controller
{
    public function index(Request $request)
    {
        $instansis = Instansi::when($request->search, fn ($q, $v) => $q->where('nama_instansi', 'like', "%{$v}%"))
            ->latest()->paginate(10)->withQueryString();
        return view('instansi.index', compact('instansis'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_instansi' => ['required', 'string', 'max:150'],
            'jenis' => ['required', 'in:internal,eksternal'],
            'alamat' => ['nullable', 'string'],
            'telepon' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email'],
            'kontak_person' => ['nullable', 'string', 'max:100'],
        ]);

        Instansi::create($data);
        ActivityLog::catat('create', 'instansi', "Menambah instansi {$data['nama_instansi']}");

        return back()->with('success', 'Instansi berhasil ditambahkan.');
    }

    public function update(Request $request, Instansi $instansi)
    {
        $data = $request->validate([
            'nama_instansi' => ['required', 'string', 'max:150'],
            'jenis' => ['required', 'in:internal,eksternal'],
            'alamat' => ['nullable', 'string'],
            'telepon' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email'],
            'kontak_person' => ['nullable', 'string', 'max:100'],
        ]);

        $instansi->update($data);
        ActivityLog::catat('update', 'instansi', "Mengubah instansi {$instansi->nama_instansi}");

        return back()->with('success', 'Instansi berhasil diperbarui.');
    }

    public function destroy(Instansi $instansi)
    {
        $nama = $instansi->nama_instansi;
        $instansi->delete();
        ActivityLog::catat('delete', 'instansi', "Menghapus instansi {$nama}");

        return back()->with('success', 'Instansi berhasil dihapus.');
    }
}
