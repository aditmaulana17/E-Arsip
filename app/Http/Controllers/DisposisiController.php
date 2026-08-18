<?php

namespace App\Http\Controllers;

use App\Http\Requests\DisposisiRequest;
use App\Models\ActivityLog;
use App\Models\Disposisi;
use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DisposisiController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $disposisis = Disposisi::with(['suratMasuk', 'dari', 'kepada'])
            ->when($request->status, fn ($q, $v) => $q->where('status', $v))
            ->when(! $user->isAdmin(), fn ($q) => $q->where('kepada_user_id', $user->id))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('disposisi.index', compact('disposisis'));
    }

    public function create(SuratMasuk $suratMasuk)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $users = User::where('is_active', true)
            ->where('id', '!=', $user->id)
            ->orderBy('name')
            ->get();

        return view('disposisi.create', compact('suratMasuk', 'users'));
    }

    public function store(DisposisiRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $data = $request->validated();
        $data['dari_user_id'] = $user->id;

        $disposisi = Disposisi::create($data);

        // Update status surat masuk otomatis
        $disposisi->suratMasuk->update(['status' => 'didisposisikan']);

        ActivityLog::catat('disposisi', 'disposisi', "Mendisposisikan surat {$disposisi->suratMasuk->nomor_agenda} kepada {$disposisi->kepada->name}");

        return redirect()->route('disposisi.index')->with('success', 'Disposisi berhasil dikirim.');
    }

    public function show(Disposisi $disposisi)
    {
        $disposisi->load(['suratMasuk', 'dari', 'kepada']);
        return view('disposisi.show', compact('disposisi'));
    }

    public function edit(Disposisi $disposisi)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $users = User::where('is_active', true)
            ->where('id', '!=', $user->id)
            ->orderBy('name')
            ->get();

        return view('disposisi.edit', compact('disposisi', 'users'));
    }

    public function update(DisposisiRequest $request, Disposisi $disposisi)
    {
        $data = $request->validated();
        $disposisi->update($data);

        ActivityLog::catat('update', 'disposisi', "Mengubah data disposisi #{$disposisi->id}");

        return redirect()->route('disposisi.index')->with('success', 'Data disposisi berhasil diperbarui.');
    }

    public function destroy(Disposisi $disposisi)
    {
        $nomorAgenda = $disposisi->suratMasuk->nomor_agenda ?? '-';
        $disposisi->delete();

        ActivityLog::catat('delete', 'disposisi', "Menghapus disposisi untuk surat {$nomorAgenda}");

        return redirect()->route('disposisi.index')->with('success', 'Disposisi berhasil dihapus.');
    }

    public function updateStatus(Request $request, Disposisi $disposisi)
    {
        $request->validate(['status' => ['required', 'in:menunggu,diproses,selesai']]);
        $disposisi->update(['status' => $request->status]);

        if ($request->status === 'selesai' && $disposisi->suratMasuk->disposisi()->where('status', '!=', 'selesai')->doesntExist()) {
            $disposisi->suratMasuk->update(['status' => 'selesai']);
        }

        ActivityLog::catat('update', 'disposisi', "Mengubah status disposisi #{$disposisi->id} menjadi {$request->status}");

        return back()->with('success', 'Status disposisi berhasil diperbarui.');
    }
}