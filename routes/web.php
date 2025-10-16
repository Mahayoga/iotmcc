<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RuanganPerebusanController;
use App\Http\Controllers\RuanganFermentasiController;
use App\Http\Controllers\RuanganPengeringanController;
use App\Models\GudangModel;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('user.index');
})->name('user.index');

Route::middleware(['auth', 'verified'])->group(function() {
    Route::resource('dashboard', DashboardController::class);
    Route::resource( 'ruang-perebusan', RuanganPerebusanController::class );
        Route::prefix('ruang-perebusan')->group(function() {
            Route::get('/data/sensor/suhu/{id}', [RuanganPerebusanController::class, 'getDataSuhu'])->name('ruang-perebusan.getDataSuhu');
        });
    Route::resource('ruang-fermentasi', RuanganFermentasiController::class);
        Route::prefix('ruang-fermentasi')->group(function() {
            Route::get('/data/sensor/suhu/{id}', [RuanganFermentasiController::class, 'getDataSuhu'])->name('ruang-fermentasi.getDataSuhu');
        });
    Route::resource('ruang-pengeringan', RuanganPengeringanController::class);
        Route::prefix('ruang-pengeringan')->group(function() {
            Route::get('/data/sensor/suhu/{id}', [RuanganPengeringanController::class, 'getDataSuhu'])->name('ruang-pengeringan.getDataSuhu');
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
