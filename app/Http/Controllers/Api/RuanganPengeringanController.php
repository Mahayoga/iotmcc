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


    /**
     * Mengambil data spesifik untuk Blower dan menghitung durasi aktif.
     * Mengambil 11 data terbaru.
     */
    public function getDataBlower(string $id)
    {
        $dataBlower = [];
        $dataWaktuBlower = [];
        $statusRuangan = 1; // default

        $dataGudang = GudangModel::findOrFail($id);
        $dataRuangan = $dataGudang->getDataRuangan;

        foreach ($dataRuangan as $ruangan) {
            if ($ruangan->tipe_ruangan == 3) {
                $statusRuangan = $ruangan->status_ruangan;

                foreach ($ruangan->getDataSensor as $sensor) {
                    if ($sensor->flag_sensor == 'blower') {
                        // Ambil 11 log terbaru dari LogModeBlowerModel
                        $logs = $sensor->getDataLogModeBlower()
                            ->orderBy('created_at', 'desc')
                            ->limit(11) // Diubah dari 30 menjadi 11
                            ->get();

                        foreach ($logs as $log) {
                            $dataBlower[] = $log->nilai_sensor;
                            $dataWaktuBlower[] = date('G:i:s', $log->created_at->timestamp);
                        }
                    }
                }
            }
        }

        // Perhitungan durasi aktif
        $durasiAktif = 0;
        $lastOnTime = null;
        
        $reversedDataBlower = array_reverse($dataBlower);
        $reversedWaktuBlower = array_reverse($dataWaktuBlower);

        foreach ($reversedDataBlower as $i => $nilai) {
            $time = $reversedWaktuBlower[$i];
            if ($nilai == 1) {
                if ($lastOnTime === null) {
                    $lastOnTime = $time;
                }
            } elseif ($nilai == 0 && $lastOnTime) {
                $durasiAktif += (strtotime($time) - strtotime($lastOnTime));
                $lastOnTime = null;
            }
        }
        
        if ($lastOnTime) {
            $durasiAktif += (time() - strtotime($lastOnTime));
        }

        $statusBlower = $dataBlower[0] ?? 0;

        return response()->json([
            'status' => true,
            'statusRuangan' => $statusRuangan,
            'statusBlower' => $statusBlower,
            'durasiAktif' => round($durasiAktif / 60), // Durasi dalam menit
            'dataBlower' => $dataBlower,
            'dataWaktuBlower' => $dataWaktuBlower,
        ]);
    }


    /**
     * Mengubah status blower (ON/OFF).
     */
    public function toggleBlower(string $id)
    {
        $dataGudang = GudangModel::findOrFail($id);
        $dataRuangan = $dataGudang->getDataRuangan;

        foreach ($dataRuangan as $ruangan) {
            if ($ruangan->tipe_ruangan == 3) {
                foreach ($ruangan->getDataSensor as $sensor) {
                    if ($sensor->flag_sensor == 'blower') {
                        
                        $mode = $sensor->getDataModeBlower()->latest()->first();

                        if (!$mode) {
                            $mode = $sensor->getDataModeBlower()->create(['nilai_sensor' => 0]);
                        }

                        $mode->nilai_sensor = $mode->nilai_sensor == 1 ? 0 : 1;
                        $mode->save();

                        $sensor->getDataLogModeBlower()->create([
                            'nilai_sensor' => $mode->nilai_sensor,
                            'created_at' => now()
                        ]);

                        return response()->json([
                            'status' => true,
                            'message' => 'Status blower diperbarui',
                            'statusBlower' => $mode->nilai_sensor
                        ]);
                    }
                }
            }
        }

        return response()->json(['status' => false, 'message' => 'Sensor blower tidak ditemukan'], 404);
    }
}
