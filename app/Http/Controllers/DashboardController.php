<?php

namespace App\Http\Controllers;

use App\Models\Disposisi;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Menampilkan data utama untuk Dashboard
     */
    public function index()
    {
        $userId = Auth::id();

        // 1. Stat Cards Data
        $totalSuratMasuk   = SuratMasuk::count();
        $totalSuratKeluar  = SuratKeluar::count();
        $suratPending      = SuratMasuk::whereIn('status', ['pending', 'baru', 'proses'])->count();
        
        // Disposisi Menunggu milik user yang sedang login
        $disposisiMenunggu = Disposisi::where('kepada_user_id', $userId)
            ->where('status', 'menunggu')
            ->count();

        // 2. Disposisi Widget (Menggunakan relasi 'dari' sesuai Model Disposisi)
        $listDisposisi = Disposisi::with(['suratMasuk', 'dari'])
            ->where('kepada_user_id', $userId)
            ->where('status', 'menunggu')
            ->latest()
            ->take(5)
            ->get();

        // 3. Tabel Surat Masuk Terbaru (5 Terbaru)
        $suratMasukTerbaru = SuratMasuk::with(['instansi', 'kategori'])
            ->latest()
            ->take(5)
            ->get();

        // 4. Data Grafik Chart.js (12 Bulan Terakhir)
        // Sebelumnya 24 query COUNT terpisah. Sekarang cukup 2 query agregasi.
        $startDate = Carbon::now()->startOfMonth()->subMonths(11);

        $masukPerBulan = SuratMasuk::query()
            ->selectRaw('YEAR(created_at) as tahun, MONTH(created_at) as bulan, COUNT(*) as total')
            ->where('created_at', '>=', $startDate)
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->get()
            ->keyBy(fn ($row) => sprintf('%04d-%02d', $row->tahun, $row->bulan));

        $keluarPerBulan = SuratKeluar::query()
            ->selectRaw('YEAR(created_at) as tahun, MONTH(created_at) as bulan, COUNT(*) as total')
            ->where('created_at', '>=', $startDate)
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->get()
            ->keyBy(fn ($row) => sprintf('%04d-%02d', $row->tahun, $row->bulan));

        $chartLabels = [];
        $chartDataMasuk = [];
        $chartDataKeluar = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->startOfMonth()->subMonths($i);
            $key = $date->format('Y-m');

            $chartLabels[] = $date->translatedFormat('M');
            $chartDataMasuk[] = (int) ($masukPerBulan[$key]->total ?? 0);
            $chartDataKeluar[] = (int) ($keluarPerBulan[$key]->total ?? 0);
        }

        // Return ke view
        return view('dashboard.index', compact(
            'totalSuratMasuk',
            'totalSuratKeluar',
            'suratPending',
            'disposisiMenunggu',
            'listDisposisi',
            'suratMasukTerbaru',
            'chartLabels',
            'chartDataMasuk',
            'chartDataKeluar'
        ));
    }
}