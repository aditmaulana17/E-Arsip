<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DisposisiController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\InstansiController;
use App\Http\Controllers\KategoriSuratController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

// Auth
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('login.attempt');
});
Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Surat Masuk
    Route::get('surat-masuk/{suratMasuk}/label', [SuratMasukController::class, 'cetakLabel'])->name('surat-masuk.label');
    Route::resource('surat-masuk', SuratMasukController::class);

    // Surat Keluar
    Route::resource('surat-keluar', SuratKeluarController::class);

    // Disposisi
    Route::get('surat-masuk/{suratMasuk}/disposisi/create', [DisposisiController::class, 'create'])->name('disposisi.create');
    Route::patch('disposisi/{disposisi}/status', [DisposisiController::class, 'updateStatus'])->name('disposisi.status');
    Route::resource('disposisi', DisposisiController::class)->only(['index', 'store']);

    // Export
    Route::get('export/surat-masuk/excel', [ExportController::class, 'suratMasukExcel'])->name('export.surat-masuk.excel');
    Route::get('export/surat-masuk/pdf', [ExportController::class, 'suratMasukPdf'])->name('export.surat-masuk.pdf');
    Route::get('export/surat-keluar/excel', [ExportController::class, 'suratKeluarExcel'])->name('export.surat-keluar.excel');
    Route::get('export/surat-keluar/pdf', [ExportController::class, 'suratKeluarPdf'])->name('export.surat-keluar.pdf');

    // Master data & pengguna khusus admin
    Route::middleware('role:admin')->group(function () {
        Route::resource('kategori', KategoriSuratController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('instansi', InstansiController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('users', UserController::class)->only(['index', 'store', 'update', 'destroy']);
    });
});
