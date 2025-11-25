<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SensorDataService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class DataBlanchingAPIController extends Controller
{
    private $sensorService;

    public function __construct(SensorDataService $sensorService)
    {
        $this->sensorService = $sensorService;
    }

    public function getDataSensorAPI(string $id)
    {
        try {
            $data = $this->sensorService->getBlanchingData($id);

            return response()->json(array_merge([
                'status' => true,
            ], $data));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Data Gudang tidak ditemukan',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data sensor: '.$e->getMessage(),
            ], 500);
        }
    }

    public function toggleTimerAPI(string $id)
    {
        try {
            $data = $this->sensorService->toggleTimer($id);

            return response()->json([
                'status' => true,
                'data' => $data,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Data Gudang/Sensor tidak ditemukan',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengubah status timer: '.$e->getMessage(),
            ], 500);
        }
    }

    public function getDataTimerAPI(string $id)
    {
        try {
            $data = $this->sensorService->getTimerData($id);

            return response()->json([
                'status' => true,
                'dataTimer' => $data,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Data Gudang tidak ditemukan',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data timer: '.$e->getMessage(),
            ], 500);
        }
    }

    public function setLimitTimerAPI(Request $request, string $id)
    {
        $request->validate([
            'limit_timer' => 'required|integer|min:1',
            'flag_sensor' => 'required|string|in:timer_1,timer_2',
        ]);

        try {
            $result = $this->sensorService->setTimerLimit($id, $request->limit_timer, $request->flag_sensor);

            if ($result) {
                return response()->json([
                    'status' => true,
                    'message' => 'Timer berhasil diset menjadi ' . $request->limit_timer . ' menit.',
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Sensor tidak ditemukan',
            ], 404);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Data Gudang tidak ditemukan',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal set timer: '.$e->getMessage(),
            ], 500);
        }
    }
}
