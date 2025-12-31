<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GudangModel;
use App\Models\RuanganModel;
use Illuminate\Http\JsonResponse;

class DataRiwayatAPIController extends Controller
{
    /**
     * Get all warehouses
     */
    public function getGudang(): JsonResponse
    {
        $gudang = GudangModel::all();

        return response()->json([
            'status' => true,
            'data' => $gudang,
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
            'data' => $ruangan,
        ]);
    }

    /**
     * Get sensor data by room ID and date
     */
    public function getDataSensor(string $id, string $tgl): JsonResponse
    {
        $dataRuangan = RuanganModel::find($id);

        if (! $dataRuangan) {
            return response()->json([
                'status' => false,
                'message' => 'Ruangan tidak ditemukan!',
            ], 404);
        }

        $dataSensor = [];
        $sensors = $dataRuangan->getDataSensor;

        foreach ($sensors as $sensor) {

            if (str_contains($sensor->flag_sensor, 'timer') || str_contains($sensor->flag_sensor, 'blower')) {
                continue;
            }

            $nilaiSensorData = $sensor->getDataNilaiSensor()
                ->selectRaw('
        AVG(nilai_sensor) as avg_value,
        FLOOR(MINUTE(created_at)/15) as menit_group,
        HOUR(created_at) as jam_group
    ')
                ->whereDate('created_at', $tgl)
                ->groupBy('jam_group', 'menit_group')
                ->orderBy('jam_group', 'asc')
                ->orderBy('menit_group', 'asc')
                ->get();

            if ($nilaiSensorData->isEmpty()) {
                continue;
            }

            $nilaiSensorTemp = [];
            $waktuSensorTemp = [];

            foreach ($nilaiSensorData as $val) {

                $formattedTime = sprintf('%02d:%02d', $val->jam_group, $val->menit_group * 15);

                $nilaiSensorTemp[] = number_format($val->avg_value, 2);
                $waktuSensorTemp[] = $formattedTime;
            }

            $avgHarian = count($nilaiSensorTemp) > 0
                ? number_format(array_sum($nilaiSensorTemp) / count($nilaiSensorTemp), 1)
                : 0;

            array_push($dataSensor, [
                'type' => 'sensor',
                'flag_sensor' => $sensor->flag_sensor,
                'value' => $nilaiSensorTemp,
                'avg' => $avgHarian,
                'time_label' => $waktuSensorTemp,
            ]);
        }

        if (empty($dataSensor)) {
            return response()->json([
                'status' => false,
                'message' => 'Data pada tanggal ini tidak ditemukan untuk semua sensor!',
                'date' => $tgl,
            ], 404);
        }

        return response()->json([
            'status' => true,
            'dataSensor' => $dataSensor,
            'namaRuangan' => $dataRuangan->nama_ruangan,
        ]);
    }
}
