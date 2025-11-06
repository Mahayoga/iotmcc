<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\GudangModel;
use App\Models\NilaiSensorModel;
use App\Models\SensorModel;
use Illuminate\Http\Request;

class RuanganFermentasiController extends Controller
{
    public function getDataSensor(string $id) {
        $dataGudang = GudangModel::findOrFail($id);
        $dataRuangan = $dataGudang->getDataRuangan;
        $statusRuangan = 1;

        $allSensorData = [];
        $dataSensor = [];
        $dataWaktuSensor = [];
        $averagedData = [
            'suhu' => ['values' => [], 'waktu' => []],
            'kelembaban' => ['values' => [], 'waktu' => []]
        ];

        foreach ($dataRuangan as $ruangan) {
            if($ruangan->tipe_ruangan == 2) {
                $statusRuangan = $ruangan->status_ruangan;

                foreach($ruangan->getDataSensor as $sensor) {
                    $nilaiSensor = [];
                    $waktuSensor = [];

                    // Ambil 11 data terbaru
                    $dataNilaiSensor = $sensor->getDataNilaiSensor()
                        ->orderBy('created_at', 'desc')
                        ->limit(11)
                        ->get();

                    foreach($dataNilaiSensor as $nilai) {
                        $nilaiSensor[] = (float) $nilai->nilai_sensor;
                        $waktuSensor[] = date('G:i:s', $nilai->created_at->timestamp);
                    }

                    // Simpan data sensor individual
                    $allSensorData[$sensor->flag_sensor] = [
                        'values' => $nilaiSensor,
                        'waktu' => $waktuSensor,
                        'avg' => count($nilaiSensor) > 0 ? array_sum($nilaiSensor) / count($nilaiSensor) : 0
                    ];

                    // Format data sensor untuk response
                    $dataSensor[] = [
                        'type' => 'sensor',
                        'flag_sensor' => $sensor->flag_sensor,
                        'value' => $nilaiSensor,
                        'avg' => number_format($allSensorData[$sensor->flag_sensor]['avg'], 1),
                    ];

                    // Format data waktu untuk response
                    $dataWaktuSensor[] = [
                        'type' => 'waktu',
                        'flag_sensor' => $sensor->flag_sensor,
                        'value' => $waktuSensor
                    ];
                }
            }
        }

        // PROSES AVERAGING UNTUK SETIAP DATA POINT
        $averagedValues = [];

        // Ambil data suhu_1 dan suhu_2
        $suhu1 = $allSensorData['suhu_1'] ?? null;
        $suhu2 = $allSensorData['suhu_2'] ?? null;

        // Ambil data kelembaban_1 dan kelembaban_2
        $kelembaban1 = $allSensorData['kelembaban_1'] ?? null;
        $kelembaban2 = $allSensorData['kelembaban_2'] ?? null;

        // Averaging untuk setiap data point (11 data)
        for ($i = 0; $i < 11; $i++) {
            // Averaging suhu
            $suhuAvg = 0;
            $suhuCount = 0;

            if ($suhu1 && isset($suhu1['values'][$i])) {
                $suhuAvg += $suhu1['values'][$i];
                $suhuCount++;
            }
            if ($suhu2 && isset($suhu2['values'][$i])) {
                $suhuAvg += $suhu2['values'][$i];
                $suhuCount++;
            }

            if ($suhuCount > 0) {
                $averagedValues['suhu']['values'][] = number_format($suhuAvg / $suhuCount, 1);
                // Ambil waktu dari sensor mana saja yang ada data
                $averagedValues['suhu']['waktu'][] = $suhu1['waktu'][$i] ?? $suhu2['waktu'][$i] ?? '00:00:00';
            }

            $kelembabanAvg = 0;
            $kelembabanCount = 0;

            if ($kelembaban1 && isset($kelembaban1['values'][$i])) {
                $kelembabanAvg += $kelembaban1['values'][$i];
                $kelembabanCount++;
            }
            if ($kelembaban2 && isset($kelembaban2['values'][$i])) {
                $kelembabanAvg += $kelembaban2['values'][$i];
                $kelembabanCount++;
            }

            if ($kelembabanCount > 0) {
                $averagedValues['kelembaban']['values'][] = number_format($kelembabanAvg / $kelembabanCount, 1);
                $averagedValues['kelembaban']['waktu'][] = $kelembaban1['waktu'][$i] ?? $kelembaban2['waktu'][$i] ?? '00:00:00';
            }
        }

        $globalAverages = [];

        if (isset($averagedValues['suhu']['values'])) {
            $suhuValues = array_map('floatval', $averagedValues['suhu']['values']);
            $globalAverages['suhu'] = number_format(array_sum($suhuValues) / count($suhuValues), 1);
        }

        if (isset($averagedValues['kelembaban']['values'])) {
            $kelembabanValues = array_map('floatval', $averagedValues['kelembaban']['values']);
            $globalAverages['kelembaban'] = number_format(array_sum($kelembabanValues) / count($kelembabanValues), 1);
        }

        return response()->json([
            'status' => true,
            'dataSensor' => $dataSensor,
            'dataWaktuSensor' => $dataWaktuSensor,
            'statusRuangan' => $statusRuangan,
            'avg' => $globalAverages,
            'averaged_data' => $averagedValues,
            'sensor_info' => [
                'total_sensors' => count($allSensorData),
                'suhu_sensors' => [$suhu1 ? 'suhu_1' : null, $suhu2 ? 'suhu_2' : null],
                'kelembaban_sensors' => [$kelembaban1 ? 'kelembaban_1' : null, $kelembaban2 ? 'kelembaban_2' : null]
            ]
        ]);
    }
}
