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


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {

    // =========================
    // DATA KENDARAAN
    // =========================

    $totalKendaraan = Kendaraan::count();

    $totalHV = Kendaraan::where('kategori', 'HV')->count();

    $totalHE = Kendaraan::where('kategori', 'HE')->count();

    $totalUmum = Kendaraan::where('kategori', 'Kendaraan')->count();


    // =========================
    // STATUS KENDARAAN
    // =========================

    $kendaraanAktif = Kendaraan::where('status', 'Aktif')->count();

    $kendaraanTidakAktif = Kendaraan::where('status', 'Tidak Aktif')->count();


    // =========================
    // DATA DOKUMEN
    // =========================

    $totalDokumen = Dokumen::count();

    $expired = Dokumen::whereDate(
        'tanggal_expired',
        '<',
        now()
    )->count();

    $akanExpired = Dokumen::whereBetween(
        'tanggal_expired',
        [
            now()->toDateString(),
            now()->addDays(30)->toDateString()
        ]
    )->count();


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

    Route::resource('kendaraan', KendaraanController::class)->middleware('auth');

    Route::post('/kendaraan/import', [KendaraanController::class, 'import'])
        ->middleware('auth')
        ->name('kendaraan.import');
    
        Route::resource('dokumen', DokumenController::class)
        ->parameters([
            'dokumen' => 'dokumen',
        ]);
    Route::resource('pic', PicController::class)->middleware('auth');

    Route::resource('users', UserController::class)
        ->except(['show'])
        ->middleware('auth');

Route::middleware('auth')->group(function () {

    Route::get('/admin/settings', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/admin/settings', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::put('/admin/settings/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.password');

    Route::get('/notifikasi', [NotifikasiController::class, 'index'])
        ->name('notifikasi.index');


});

require __DIR__.'/auth.php';