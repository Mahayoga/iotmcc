<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GudangModel;
use App\Models\RuanganModel;
use App\Models\SensorModel;
use App\Models\NilaiSensorModel;
use Illuminate\Http\JsonResponse;

class RiwayatDataController extends Controller
{
    /**
     * Get all warehouses
     */
    public function getGudang(): JsonResponse
    {
        $gudang = GudangModel::all();

        return response()->json([
            'status' => true,
            'data' => $gudang
        ]);
    }

    /**
     * Get rooms by warehouse ID
     */
    public function getRuangan($idGudang): JsonResponse
    {
        $ruangan = RuanganModel::where('id_gudang', $idGudang)->get();

        return response()->json([
            'status' => true,
            'data' => $ruangan
        ]);
    }

    /**
     * Get sensor data by room ID and date
     */
    public function getDataSensor(string $id, string $tgl): JsonResponse
    {
        $dataSensor = [];
        $dataRuangan = RuanganModel::find($id);

        if (!$dataRuangan) {
            return response()->json([
                'status' => false,
                'message' => 'Ruangan tidak ditemukan!'
            ], 404);
        }

        $nilaiSensorTemp = [];
        $waktuSensorTemp = [];

        foreach($dataRuangan->getDataSensor as $sensor) {
            if(!str_contains($sensor->flag_sensor, 'timer') && !str_contains($sensor->flag_sensor,'blower')) {

                $nilaiSensorData = $sensor->getDataNilaiSensor()
                    ->where('created_at', 'LIKE', '%' . $tgl . '%')
                    ->orderBy('created_at', 'desc')
                    ->get();

                if($nilaiSensorData->count() <= 0) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data pada tanggal ini tidak ditemukan!',
                        'date' => $tgl
                    ], 404);
                }

                foreach($nilaiSensorData as $nilaiSensor) {
                    $nilaiSensorTemp[] = $nilaiSensor->nilai_sensor;
                    $waktuSensorTemp[] = date('H:i:s', $nilaiSensor->created_at->timestamp);
                }

                array_push($dataSensor, [
                    'type' => 'sensor',
                    'flag_sensor' => $sensor->flag_sensor,
                    'value' => $nilaiSensorTemp,
                    'avg' => number_format(array_sum($nilaiSensorTemp) / count($nilaiSensorTemp), 1),
                    'time_label' => $waktuSensorTemp
                ]);

                $nilaiSensorTemp = [];
                $waktuSensorTemp = [];
            }
        }

        return response()->json([
            'status' => true,
            'dataSensor' => $dataSensor,
            'namaRuangan' => $dataRuangan->nama_ruangan,
        ]);
    }
}
