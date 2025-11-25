<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SensorDataService;

class DataFermentasiAPIController extends Controller
{
    private $sensorService;

    public function __construct(SensorDataService $sensorService)
    {
        $this->sensorService = $sensorService;
    }

    public function getDataSensorAPI(string $id)
    {
        try {
            $data = $this->sensorService->getFermentasiData($id);

            return response()->json(array_merge([
                'status' => true,
            ], $data));
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data sensor: '.$e->getMessage(),
            ], 500);
        }
    }
}
