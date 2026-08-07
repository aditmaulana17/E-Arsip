<?php

namespace App\Http\Controllers;

use App\Models\Disposisi;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSuratMasuk = SuratMasuk::count();
        $totalSuratKeluar = SuratKeluar::count();
        $suratBelumDiproses = SuratMasuk::where('status', 'baru')->count();
        $disposisiMenunggu = Disposisi::where('kepada_user_id', auth()->id())
            ->where('status', 'menunggu')->count();

        // Statistik per bulan (12 bulan terakhir) untuk chart
        $statistikMasuk = SuratMasuk::selectRaw("DATE_FORMAT(tanggal_terima, '%Y-%m') as bulan, COUNT(*) as total")
            ->where('tanggal_terima', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('bulan')->orderBy('bulan')->pluck('total', 'bulan');

        $statistikKeluar = SuratKeluar::selectRaw("DATE_FORMAT(tanggal_surat, '%Y-%m') as bulan, COUNT(*) as total")
            ->where('tanggal_surat', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('bulan')->orderBy('bulan')->pluck('total', 'bulan');

        $suratTerbaru = SuratMasuk::with(['instansi', 'kategori'])->latest()->take(5)->get();

        $disposisiSaya = Disposisi::with(['suratMasuk', 'dari'])
            ->where('kepada_user_id', auth()->id())
            ->where('status', '!=', 'selesai')
            ->latest()->take(5)->get();

        return view('dashboard.index', compact(
            'totalSuratMasuk', 'totalSuratKeluar', 'suratBelumDiproses',
            'disposisiMenunggu', 'statistikMasuk', 'statistikKeluar',
            'suratTerbaru', 'disposisiSaya'
        ));
    }
}
