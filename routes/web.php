<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KendaraanController;
use App\Models\Kendaraan;
use App\Http\Controllers\DokumenController;
use App\Models\Dokumen;
use Carbon\Carbon;
use App\Http\Controllers\PicController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\GlobalSearchController;
use App\Http\Controllers\AuditLogController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    $totalKendaraan = Kendaraan::count();
    $totalHV = Kendaraan::where('kategori', 'HV')->count();
    $totalHE = Kendaraan::where('kategori', 'HE')->count();
    $totalUmum = Kendaraan::where('kategori', 'Kendaraan')->count();

    $kendaraanAktif = Kendaraan::where('status', 'Aktif')->count();
    $kendaraanTidakAktif = Kendaraan::where('status', 'Tidak Aktif')->count();

    $totalDokumen = Dokumen::count();

    $expired = Dokumen::whereDate('tanggal_expired', '<', now())->count();

    $akanExpired = Dokumen::whereBetween('tanggal_expired', [
        now()->toDateString(),
        now()->addDays(30)->toDateString(),
    ])->count();

    return view('dashboard', compact(
        'totalKendaraan',
        'totalHV',
        'totalHE',
        'totalUmum',
        'kendaraanAktif',
        'kendaraanTidakAktif',
        'totalDokumen',
        'expired',
        'akanExpired'
    ));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/hse', function () {
    return view('hse.index');
})->middleware('auth')->name('hse.index');

Route::middleware('auth')->group(function () {
    Route::delete('/kendaraan/bulk-destroy', [KendaraanController::class, 'bulkDestroy'])
        ->name('kendaraan.bulkDestroy');

    Route::get('/kendaraan/export', [KendaraanController::class, 'export'])
        ->name('kendaraan.export');

    Route::get('/kendaraan/{kendaraan}/export', [KendaraanController::class, 'exportDetail'])
        ->name('kendaraan.exportDetail');

    Route::resource('kendaraan', KendaraanController::class);

    Route::post('/kendaraan/import', [KendaraanController::class, 'import'])
        ->name('kendaraan.import');

    Route::delete('/dokumen/bulk-destroy', [DokumenController::class, 'bulkDestroy'])
        ->name('dokumen.bulkDestroy');

    Route::get('/dokumen/export', [DokumenController::class, 'export'])
        ->name('dokumen.export');

    Route::resource('dokumen', DokumenController::class)
        ->parameters(['dokumen' => 'dokumen']);

    // Semua pengguna yang login boleh melihat daftar PIC.
    Route::get('/pic', [PicController::class, 'index'])
        ->name('pic.index');

    // Hanya Admin dan Super Admin yang boleh mengelola data PIC.
    Route::middleware('role:admin,super_admin')->group(function () {
        Route::resource('pic', PicController::class)->except(['index']);
    });

    Route::get('/search/global', [GlobalSearchController::class, 'index'])
        ->name('search.global');

    Route::middleware('role:admin,super_admin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
        Route::get('/riwayat-aktivitas', [AuditLogController::class, 'index'])
            ->name('audit-logs.index');
    });


    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.password');

    // URL lama untuk halaman pengaturan tetap berfungsi.
    Route::redirect('/admin/settings', '/profile');
    Route::redirect('/admin/settings/password', '/profile');

    Route::get('/notifikasi', [NotifikasiController::class, 'index'])
        ->name('notifikasi.index');

});

require __DIR__ . '/auth.php';