<?php

use App\Http\Controllers\AlatBleachingController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DataBlanchingAPIController;
use App\Http\Controllers\Api\DataFermentasiAPIController;
use App\Http\Controllers\Api\DataPengeringanAPIController;
use App\Http\Controllers\Api\GudangController;
use App\Http\Controllers\Api\DataRiwayatAPIController;
use App\Http\Controllers\NilaiBlowerAPIController;
use App\Http\Controllers\NilaiSensorAPIController;
use App\Http\Controllers\NilaiTimerAPIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/update-fcm-token', [AuthController::class, 'updateFcmToken']);
});

Route::prefix('v1')->group(function () {
    Route::get('/data-blanching/{id}/data-sensor', [DataBlanchingAPIController::class, 'getDataSensorAPI']);
    Route::get('/data-blanching/{id}/timer', [DataBlanchingAPIController::class, 'getDataTimerAPI']);
    Route::post('/data-blanching/{id}/toggle-timer', [DataBlanchingAPIController::class, 'toggleTimerAPI']);
    Route::post('/data-blanching/{id}/limit-timer', [DataBlanchingAPIController::class, 'setLimitTimerAPI']);
    Route::get('/data-fermentasi/{id}/data-sensor', [DataFermentasiAPIController::class, 'getDataSensorAPI']);
    Route::get('/data-pengeringan/{id}/data-sensor', [DataPengeringanAPIController::class, 'getDataSensorAPI']);
    Route::get('/data-pengeringan/{sensorId}/data-blower', [DataPengeringanAPIController::class, 'getDataStatusBlower']);
    Route::post('/data-pengeringan/{sensorId}/toggle-blower', [DataPengeringanAPIController::class, 'toggleBlower']);
});

Route::prefix('gudang')->group(function () {
    Route::get('/', [GudangController::class, 'index']);
    Route::get('/active', [GudangController::class, 'getActiveGudang']);
    Route::get('/{id}', [GudangController::class, 'show']);
    Route::get('/{id}/with-ruangan', [GudangController::class, 'getWithRuangan']);
});

Route::prefix('v1/riwayat')->group(function () {
    Route::get('/gudang/{idGudang}/ruangan', [DataRiwayatAPIController::class, 'getRuangan']);
    Route::get('/ruangan/{id}/sensor/{tgl}', [DataRiwayatAPIController::class, 'getDataSensor']);
    Route::get('/notifikasi', [NilaiSensorAPIController::class, 'getRiwayatNotifikasi']);
});

Route::prefix('send/')->group(function () {
    Route::post('/nilai/sensor', [NilaiSensorAPIController::class, 'store']);
    Route::post('/nilai/timer', [NilaiTimerAPIController::class, 'store']);
    Route::post('/nilai/blower', [NilaiBlowerAPIController::class, 'store']);
});

Route::prefix('check/')->group(function () {
    Route::get('/nilai/blower/{id}', [NilaiBlowerAPIController::class, 'show']);
    Route::get('/nilai/timer/{id}', [AlatBleachingController::class, 'getDataTimer']);
});
