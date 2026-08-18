<?php

namespace App\Http\Controllers;

use App\Exports\SuratKeluarExport;
use App\Exports\SuratMasukExport;
use App\Models\ActivityLog;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use Barryvdh\DomPDF\Facade\Pdf as Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function suratMasukExcel(Request $request)
    {
        ActivityLog::catat('export', 'surat_masuk', 'Export surat masuk ke Excel');
        return Excel::download(new SuratMasukExport($request->all()), 'surat-masuk-' . now()->format('Ymd-His') . '.xlsx');
    }

    public function suratMasukPdf(Request $request)
    {
        $suratMasuks = SuratMasuk::with(['instansi', 'kategori'])
            ->filter($request->all())
            ->latest('tanggal_terima')
            ->get();

        ActivityLog::catat('export', 'surat_masuk', 'Export surat masuk ke PDF');

        $pdf = Pdf::loadView('exports.surat_masuk_pdf', compact('suratMasuks'))->setPaper('a4', 'landscape');
        return $pdf->download('surat-masuk-' . now()->format('Ymd-His') . '.pdf');
    }

    public function suratKeluarExcel(Request $request)
    {
        ActivityLog::catat('export', 'surat_keluar', 'Export surat keluar ke Excel');
        return Excel::download(new SuratKeluarExport($request->all()), 'surat-keluar-' . now()->format('Ymd-His') . '.xlsx');
    }

    public function suratKeluarPdf(Request $request)
    {
        $suratKeluars = SuratKeluar::with(['instansi', 'kategori'])
            ->filter($request->all())
            ->latest('tanggal_surat')
            ->get();

        ActivityLog::catat('export', 'surat_keluar', 'Export surat keluar ke PDF');

        $pdf = Pdf::loadView('exports.surat_keluar_pdf', compact('suratKeluars'))->setPaper('a4', 'landscape');
        return $pdf->download('surat-keluar-' . now()->format('Ymd-His') . '.pdf');
    }
}
