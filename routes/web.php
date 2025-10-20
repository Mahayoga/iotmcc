<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RuanganBlanchingController;
use App\Http\Controllers\RuanganFermentasiController;
use App\Http\Controllers\RuanganPengeringanController;
use App\Http\Controllers\RiwayatDataController;
use App\Http\Controllers\HistoryController;
use App\Models\GudangModel;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('user.index');
})->name('user.index');

Route::middleware(['auth', 'verified'])->group(function() {
    Route::resource('dashboard', DashboardController::class);
    Route::resource( 'ruang-blanching', RuanganBlanchingController::class );
        Route::prefix('ruang-blanching')->group(function() {
            Route::get('/data/sensor/sensor/{id}', [RuanganBlanchingController::class, 'getDataSensor'])->name('ruang-blanching.getDataSensor');
        });
    Route::resource('ruang-fermentasi', RuanganFermentasiController::class);
        Route::prefix('ruang-fermentasi')->group(function() {
            Route::get('/data/sensor/sensor/{id}', [RuanganFermentasiController::class, 'getDataSensor'])->name('ruang-fermentasi.getDataSensor');
        });
    Route::resource('ruang-pengeringan', RuanganPengeringanController::class);
        Route::prefix('ruang-pengeringan')->group(function() {
            Route::get('/data/sensor/sensor/{id}', [RuanganPengeringanController::class, 'getDataSensor'])->name('ruang-pengeringan.getDataSensor');
            // Route::get('/data/sensor/blower/{id}', [RuanganPengeringanController::class, 'getDataBlower'])->name('ruang-pengeringan.getDataBlower');
            // Route::post('/ruang-pengeringan/toggle-blower/{id}', [RuanganPengeringanController::class, 'toggleBlower'])->name('ruang-pengeringan.toggleBlower');
        });
    Route::resource('riwayat-data', RiwayatDataController::class);
        Route::prefix('riwayat-data')->group(function() {
            Route::get('/blanching/data/sensor/{id}', [RiwayatDataController::class, 'getDataSensor'])->name('riwayat-data.blanching.getDataSensor');
            Route::get('/fermentasi/data/sensor/{id}', [RiwayatDataController::class, 'getDataSensor'])->name('riwayat-data.fermentasi.getDataSensor');
            Route::get('/pengeringan/data/sensor/{id}', [RiwayatDataController::class, 'getDataSensor'])->name('riwayat-data.pengeringan.getDataSensor');
             Route::get('/get-ruangan/{id}', [RiwayatDataController::class, 'getRuangan'])->name('riwayat-data.getRuangan');
        });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/test/debug', function () {
    dd('hehe');
})->name('test.debug');
