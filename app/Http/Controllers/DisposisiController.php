<?php

namespace App\Http\Controllers;

use App\Http\Requests\DisposisiRequest;
use App\Models\ActivityLog;
use App\Models\Disposisi;
use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Http\Request;

class DisposisiController extends Controller
{
    public function index(Request $request)
    {
        $disposisis = Disposisi::with(['suratMasuk', 'dari', 'kepada'])
            ->when($request->status, fn ($q, $v) => $q->where('status', $v))
            ->when(! auth()->user()->isAdmin(), fn ($q) => $q->where('kepada_user_id', auth()->id()))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('disposisi.index', compact('disposisis'));
    }

    public function create(SuratMasuk $suratMasuk)
    {
        $users = User::where('is_active', true)->where('id', '!=', auth()->id())->orderBy('name')->get();
        return view('disposisi.create', compact('suratMasuk', 'users'));
    }

    public function store(DisposisiRequest $request)
    {
        $data = $request->validated();
        $data['dari_user_id'] = auth()->id();

        $disposisi = Disposisi::create($data);

        // update status surat masuk otomatis
        $disposisi->suratMasuk->update(['status' => 'didisposisikan']);

        ActivityLog::catat('disposisi', 'disposisi', "Mendisposisikan surat {$disposisi->suratMasuk->nomor_agenda} kepada {$disposisi->kepada->name}");

        return redirect()->route('disposisi.index')->with('success', 'Disposisi berhasil dikirim.');
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
