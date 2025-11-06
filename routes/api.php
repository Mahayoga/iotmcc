<?php

use App\Http\Controllers\Api\RiwayatDataController;
use App\Http\Controllers\NilaiSensorAPIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RuanganBlanchingController;
use App\Http\Controllers\Api\RuanganFermentasiController;
use App\Http\Controllers\Api\RuanganPengeringanController;
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

    Route::prefix('api-ruangan-perebusan')->group(function () {
        Route::get('data/sensor/sensor/{id}', [RuanganBlanchingController::class, 'getDataSensor']);
    });

    Route::prefix('api-ruangan-fermentasi')->group(function () {
        Route::get('data/sensor/sensor/{id}', [RuanganFermentasiController::class, 'getDataSensor']);
    });

    Route::resource('api-ruangan-pengeringan', RuanganPengeringanController::class);
        Route::prefix('api-ruangan-pengeringan')->group(function() {
            Route::get('data/sensor/sensor/{id}', [RuanganPengeringanController::class, 'getDataSensor']);
            // Route::get('/data/sensor/blower/{id}', [RuanganPengeringanController::class, 'getDataBlower']);
            // Route::post('/ruang-pengeringan/toggle-blower/{id}', [RuanganPengeringanController::class, 'toggleBlower']);
        });

    // Route::resource('api-riwayat-data', RiwayatDataController::class);
    //     Route::prefix('api-riwayat-data')->group(function() {
    //         Route::get('blanching/data/sensor/{id}/{tgl}', [RiwayatDataController::class, 'getDataSensor']);
    //         Route::get('fermentasi/data/sensor/{id}/{tgl}', [RiwayatDataController::class, 'getDataSensor']);
    //         Route::get('pengeringan/data/sensor/{id}/{tgl}', [RiwayatDataController::class, 'getDataSensor']);
    //          Route::get('get-ruangan/{id}', [RiwayatDataController::class, 'getRuangan']);
    //     });

    Route::prefix('gudang')->group(function () {
        Route::get('/', [GudangController::class, 'index']);
        Route::get('/active', [GudangController::class, 'getActiveGudang']);
        Route::get('/{id}', [GudangController::class, 'show']);
        Route::get('/{id}/with-ruangan', [GudangController::class, 'getWithRuangan']);
        Route::post('/', [GudangController::class, 'store']);
        Route::put('/{id}', [GudangController::class, 'update']);
        Route::delete('/{id}', [GudangController::class, 'destroy']);
    });

    Route::prefix('api-riwayat-data')->group(function () {
        Route::get('/gudang/{idGudang}/ruangan', [RiwayatDataController::class, 'getRuangan']);
        Route::get('/ruangan/{id}/sensor/{tgl}', [RiwayatDataController::class, 'getDataSensor']);
    });

    Route::prefix('send/')->group(function() {
        Route::post('/nilai/sensor', [NilaiSensorAPIController::class, 'store']);
    });
