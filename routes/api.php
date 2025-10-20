<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\RuanganPerebusanController;
use App\Http\Controllers\RuanganFermentasiController;
use App\Http\Controllers\RuanganPengeringanController;
use App\Http\Controllers\Api\GudangController;

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
        Route::post('/logout', [AuthController::class, 'logout']);
    });

    Route::prefix('ruangan-perebusan')->group(function () {
        Route::get('/data/sensor/suhu/{id}', [RuanganPerebusanController::class, 'getDataSuhu']);
    });

    Route::prefix('ruangan-fermentasi')->group(function () {
        Route::get('/data/sensor/sensor/{id}', [RuanganFermentasiController::class, 'getDataSensor']);
    });

    Route::resource('ruang-pengeringan', RuanganPengeringanController::class);
        Route::prefix('ruang-pengeringan')->group(function() {
            Route::get('/data/sensor/suhu/{id}', [RuanganPengeringanController::class, 'getDataSuhu']);
            Route::get('/data/sensor/blower/{id}', [RuanganPengeringanController::class, 'getDataBlower']);
            Route::post('/ruang-pengeringan/toggle-blower/{id}', [RuanganPengeringanController::class, 'toggleBlower']);
        });

    Route::prefix('gudang')->group(function () {
        Route::get('/', [GudangController::class, 'index']);
        Route::get('/active', [GudangController::class, 'getActiveGudang']);
        Route::get('/{id}', [GudangController::class, 'show']);
        Route::get('/{id}/with-ruangan', [GudangController::class, 'getWithRuangan']);
        Route::post('/', [GudangController::class, 'store']);
        Route::put('/{id}', [GudangController::class, 'update']);
        Route::delete('/{id}', [GudangController::class, 'destroy']);
    });