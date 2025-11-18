<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GudangModel;
use App\Models\NilaiSensorModel;
use App\Models\SensorModel;
use App\Models\ModeBlowerModel;
use App\Models\LogModeBlowerModel;

class RuanganPengeringanController extends Controller
{
    /**
     * Mengambil data sensor (non-blower) dan menerapkan logika averaging
     * yang sama dengan Ruangan Fermentasi.
     * Mengambil 11 data terbaru.
     */
    public function getDataSensor(string $id)
    {
        $dataSensor = [];
        $dataWaktuSensor = [];
        $dataGudang = GudangModel::findOrFail($id);
        $dataRuangan = $dataGudang->getDataRuangan;
        
        $statusRuangan = 1; // Default status
        $allSensorData = []; // Untuk menyimpan data mentah per sensor
        $averagedData = [    // Untuk menyimpan data rata-rata per titik waktu
            'suhu' => ['values' => [], 'waktu' => []],
            'kelembaban' => ['values' => [], 'waktu' => []]
        ];
        $globalAverages = []; // Untuk menyimpan rata-rata global

        foreach ($dataRuangan as $value) {
            if ($value->tipe_ruangan == 3) { // Filter Ruangan Pengeringan
                $statusRuangan = $value->status_ruangan;
                
                foreach ($value->getDataSensor as $sensor) {
                    // Filter hanya sensor non-blower
                    if (!str_contains($sensor->flag_sensor, "blower")) {
                        
                        $nilaiSensorTemp = [];
                        $waktuSensorTemp = [];

                        // Ambil 11 data terbaru
                        $dataNilaiSensor = $sensor->getDataNilaiSensor()
                            ->orderBy('created_at', 'desc')
                            ->limit(11) // Diubah dari 30 menjadi 11
                            ->get();

                        foreach ($dataNilaiSensor as $value3) {
                            $nilaiSensorTemp[] = (float) $value3->nilai_sensor;
                            $waktuSensorTemp[] = date('G:i:s', $value3->created_at->timestamp);
                        }

                        // 1. Simpan data mentah individual
                        $allSensorData[$sensor->flag_sensor] = [
                            'values' => $nilaiSensorTemp,
                            'waktu' => $waktuSensorTemp,
                            'avg' => count($nilaiSensorTemp) > 0 ? array_sum($nilaiSensorTemp) / count($nilaiSensorTemp) : 0
                        ];

                        // 2. Format untuk data sensor individual di response
                        array_push($dataSensor, [
                            'type' => 'sensor',
                            'flag_sensor' => $sensor->flag_sensor,
                            'value' => $nilaiSensorTemp,
                            'avg' => number_format($allSensorData[$sensor->flag_sensor]['avg'], 1),
                        ]);

                        // 3. Format untuk data waktu individual di response
                        array_push($dataWaktuSensor, [
                            'type' => 'waktu',
                            'flag_sensor' => $sensor->flag_sensor,
                            'value' => $waktuSensorTemp
                        ]);
                    }
                }
            }
        }

        // --- PROSES AVERAGING (Sama seperti Fermentasi, untuk 11 data) ---

        // Ambil data suhu
        $suhu1 = $allSensorData['suhu_1'] ?? null;
        $suhu2 = $allSensorData['suhu_2'] ?? null;
        
        // Ambil data kelembaban
        $kelembaban1 = $allSensorData['kelembaban_1'] ?? null;
        $kelembaban2 = $allSensorData['kelembaban_2'] ?? null;
        
        // Averaging untuk setiap data point (11 data)
        for ($i = 0; $i < 11; $i++) { // Diubah dari 30 menjadi 11
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
                $averagedData['suhu']['values'][] = number_format($suhuAvg / $suhuCount, 1);
                $averagedData['suhu']['waktu'][] = $suhu1['waktu'][$i] ?? $suhu2['waktu'][$i] ?? '00:00:00';
            }
            
            // Averaging kelembaban
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
                $averagedData['kelembaban']['values'][] = number_format($kelembabanAvg / $kelembabanCount, 1);
                $averagedData['kelembaban']['waktu'][] = $kelembaban1['waktu'][$i] ?? $kelembaban2['waktu'][$i] ?? '00:00:00';
            }
        }

        // Hitung rata-rata global untuk display
        if (!empty($averagedData['suhu']['values'])) {
            $suhuValues = array_map('floatval', $averagedData['suhu']['values']);
            $globalAverages['suhu'] = number_format(array_sum($suhuValues) / count($suhuValues), 1);
        }
        
        if (!empty($averagedData['kelembaban']['values'])) {
            $kelembabanValues = array_map('floatval', $averagedData['kelembaban']['values']);
            $globalAverages['kelembaban'] = number_format(array_sum($kelembabanValues) / count($kelembabanValues), 1);
        }

        return response()->json([
            'status' => true,
            'dataSensor' => $dataSensor,
            'dataWaktuSensor' => $dataWaktuSensor,
            'statusRuangan' => $statusRuangan,
            'avg' => $globalAverages,
            'averaged_data' => $averagedData,
            'sensor_info' => [
                'total_sensors_found' => count($allSensorData),
                'suhu_sensors' => [$suhu1 ? 'suhu_1' : null, $suhu2 ? 'suhu_2' : null],
                'kelembaban_sensors' => [$kelembaban1 ? 'kelembaban_1' : null, $kelembaban2 ? 'kelembaban_2' : null]
            ]
        ]);
    }
    
}
