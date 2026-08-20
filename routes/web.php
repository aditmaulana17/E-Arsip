<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DisposisiController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\InstansiController;
use App\Http\Controllers\KategoriSuratController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes - Aplikasi E-Arsip
|--------------------------------------------------------------------------
*/

// Redirect Root ke Login
Route::get('/', fn () => redirect()->route('login'));

// Route Utility (Clear Cache & Link Storage)
Route::get('/clear-cache', function () {
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    
    return response()->json([
        'status'  => 'success',
        'message' => 'Semua cache view, route, dan sistem berhasil dibersihkan!'
    ]);
});

Route::get('/link-storage', function () {
    try {
        Artisan::call('storage:link');
        return response()->json([
            'status'  => 'success',
            'message' => 'Symlink storage berhasil dibuat!'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status'  => 'error',
            'message' => 'Gagal membuat symlink: ' . $e->getMessage()
        ], 500);
    }
});

// --------------------------------------------------------------------------
// Auth Guest Routes (Tamu / Belum Login)
// --------------------------------------------------------------------------
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('login.attempt');

    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register'])->name('register.attempt');
});

// Logout Route
Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// --------------------------------------------------------------------------
// Auth Authenticated Routes (Sudah Login)
// --------------------------------------------------------------------------
Route::middleware('auth')->group(function () {
    
    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- SURAT MASUK ---
    // (Route khusus ditaruh DI ATAS Route::resource agar tidak tertimpa)
    Route::get('surat-masuk/{suratMasuk}/preview', [SuratMasukController::class, 'previewLampiran'])->name('surat-masuk.preview');
    Route::get('surat-masuk/{suratMasuk}/label', [SuratMasukController::class, 'cetakLabel'])->name('surat-masuk.label');
    Route::get('surat-masuk/{suratMasuk}/cetak-disposisi', [SuratMasukController::class, 'cetakDisposisi'])->name('surat-masuk.cetak-disposisi');
    Route::resource('surat-masuk', SuratMasukController::class);

    // --- SURAT KELUAR ---
    Route::resource('surat-keluar', SuratKeluarController::class);

    // --- DISPOSISI ---
    Route::get('surat-masuk/{suratMasuk}/disposisi/create', [DisposisiController::class, 'create'])->name('disposisi.create');
    Route::patch('disposisi/{disposisi}/status', [DisposisiController::class, 'updateStatus'])->name('disposisi.status');
    Route::resource('disposisi', DisposisiController::class)->only(['index', 'store', 'show', 'edit', 'update', 'destroy']);

    // --- EXPORT DATA (Excel & PDF) ---
    Route::prefix('export')->name('export.')->group(function () {
        Route::get('surat-masuk/excel', [ExportController::class, 'suratMasukExcel'])->name('surat-masuk.excel');
        Route::get('surat-masuk/pdf', [ExportController::class, 'suratMasukPdf'])->name('surat-masuk.pdf');
        Route::get('surat-keluar/excel', [ExportController::class, 'suratKeluarExcel'])->name('surat-keluar.excel');
        Route::get('surat-keluar/pdf', [ExportController::class, 'suratKeluarPdf'])->name('surat-keluar.pdf');
    });

    // --- MASTER DATA & USER MANAGEMENT (Khusus Admin) ---
    Route::middleware('role:admin')->group(function () {
        Route::resource('kategori', KategoriSuratController::class)->except(['show']);
        Route::resource('instansi', InstansiController::class)->except(['show']);
        Route::resource('users', UserController::class)->except(['show']);
    });
});

// Fallback Route (404)
Route::fallback(function () {
    if (view()->exists('errors.404')) {
        return response()->view('errors.404', [], 404);
    }
    abort(404);
});