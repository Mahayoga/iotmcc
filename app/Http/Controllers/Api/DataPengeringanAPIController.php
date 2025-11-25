<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SensorDataService;
use Illuminate\Http\Request;

class DataPengeringanAPIController extends Controller
{
    private $sensorService;

    public function __construct(SensorDataService $sensorService)
    {
        $this->sensorService = $sensorService;
    }

    public function getDataSensorAPI(string $id)
    {
        try {
            $data = $this->sensorService->getPengeringanData($id);

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data sensor: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getDataStatusBlower(string $id)
    {
        try {
            $result = $this->sensorService->getDataStatusBlower($id);

            if (!$result['status']) {
                return response()->json($result, 404);
            }

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil status blower: ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleBlower(string $id)
    {
        try {
            $result = $this->sensorService->toggleBlower($id);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengubah status blower: ' . $e->getMessage()
            ], 500);
        }
    }
}
