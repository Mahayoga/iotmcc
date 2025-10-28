<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\RuanganModel;
use App\Models\LogModeBlowerModel; // <-- Tambahkan ini
use Illuminate\Http\Request;

class RiwayatDataController extends Controller
{
    /**
     * Mengambil semua ruangan untuk dropdown di frontend
     */
    public function getRuangan($idGudang)
    {
        $ruangan = RuanganModel::where('id_gudang', $idGudang)->get(['id_ruangan', 'nama_ruangan', 'tipe_ruangan']);
        return response()->json($ruangan);
    }

    /**
     * Mengambil riwayat data sensor (non-blower)
     * $id adalah id_ruangan
     * $tgl adalah tanggal format 'Y-m-d' (contoh: '2025-10-28')
     */
    public function getDataSensor(string $id, string $tgl)
    {
        $ruangan = RuanganModel::findOrFail($id);
        $tipeRuangan = $ruangan->tipe_ruangan;

        $allSensorData = [];
        $dataSensorResponse = [];
        $dataWaktuResponse = [];
        $dataDitemukan = false; // Flag untuk mengecek data kosong

        // Solusi N+1: Eager Load semua sensor DAN nilai sensornya
        // dengan filter tanggal (whereDate)
        $sensors = $ruangan->getDataSensor()
            ->where('flag_sensor', 'not like', '%timer%')
            ->where('flag_sensor', 'not like', '%blower%')
            ->with(['getDataNilaiSensor' => function ($query) use ($tgl) {
                $query->whereDate('created_at', $tgl)
                      ->orderBy('created_at', 'asc'); // Ambil dari pagi ke malam
            }])
            ->get();

        // Loop ini sekarang tidak menjalankan query sama sekali
        foreach ($sensors as $sensor) {
            $nilaiSensorTemp = [];
            $waktuSensorTemp = [];

            // Cek apakah relasinya (yg sudah difilter) punya data
            if ($sensor->getDataNilaiSensor->isEmpty()) {
                continue; // Lanjut ke sensor berikutnya
            }

            // Jika kita sampai di sini, berarti kita menemukan data
            $dataDitemukan = true;

            foreach ($sensor->getDataNilaiSensor as $nilai) {
                $nilaiSensorTemp[] = (float) $nilai->nilai_sensor;
                $waktuSensorTemp[] = date('G:i:s', $nilai->created_at->timestamp);
            }

            $avg = count($nilaiSensorTemp) > 0 ? array_sum($nilaiSensorTemp) / count($nilaiSensorTemp) : 0;

            // 1. Simpan data mentah untuk averaging
            $allSensorData[$sensor->flag_sensor] = [
                'values' => $nilaiSensorTemp,
                'waktu' => $waktuSensorTemp,
                'avg' => $avg
            ];

            // 2. Format untuk response (data individual)
            $dataSensorResponse[] = [
                'type' => 'sensor',
                'flag_sensor' => $sensor->flag_sensor,
                'value' => $nilaiSensorTemp,
                'avg' => number_format($avg, 1),
                // 'time_label' => $waktuSensorTemp, // Ganti nama agar konsisten
            ];
            
            $dataWaktuResponse[] = [
                'type' => 'waktu',
                'flag_sensor' => $sensor->flag_sensor,
                'value' => $waktuSensorTemp
            ];
        }

        // Solusi Bug: Cek data kosong setelah semua loop selesai
        if (!$dataDitemukan) {
            return response()->json([
                'status' => false,
                'msg' => 'Data pada tanggal ' . $tgl . ' tidak ditemukan!',
            ]);
        }
        
        // --- Solusi Inkonsistensi Logika: Terapkan Logika Averaging ---
        
        $averagedData = [];
        $globalAverages = [];

        switch ($tipeRuangan) {
            case 1: // Blanching
                // Anda bisa menerapkan logika 'avg_pagi' di sini jika perlu
                // Untuk saat ini, kita hanya ambil avg global
                foreach($allSensorData as $flag => $data) {
                    $globalAverages[$flag] = number_format($data['avg'], 1);
                }
                break;

            case 2: // Fermentasi
            case 3: // Pengeringan
                // SALIN-TEMPEL LOGIKA AVERAGING DARI RuanganFermentasiController
                $suhu1 = $allSensorData['suhu_1'] ?? null;
                $suhu2 = $allSensorData['suhu_2'] ?? null;
                $kelembaban1 = $allSensorData['kelembaban_1'] ?? null;
                $kelembaban2 = $allSensorData['kelembaban_2'] ?? null;

                // Tentukan jumlah data (asumsi dari sensor pertama yg ada)
                $dataCount = count($suhu1['values'] ?? $suhu2['values'] ?? $kelembaban1['values'] ?? $kelembaban2['values'] ?? []);

                $avgSuhuValues = [];
                $avgKelembabanValues = [];
                $averagedData = [
                    'suhu' => ['values' => [], 'waktu' => []],
                    'kelembaban' => ['values' => [], 'waktu' => []]
                ];

                for ($i = 0; $i < $dataCount; $i++) {
                    // ... (Logika averaging suhu) ...
                    $suhuAvg = 0; $suhuCount = 0;
                    if ($suhu1 && isset($suhu1['values'][$i])) { $suhuAvg += $suhu1['values'][$i]; $suhuCount++; }
                    if ($suhu2 && isset($suhu2['values'][$i])) { $suhuAvg += $suhu2['values'][$i]; $suhuCount++; }
                    if ($suhuCount > 0) {
                        $val = $suhuAvg / $suhuCount;
                        $averagedData['suhu']['values'][] = number_format($val, 1);
                        $averagedData['suhu']['waktu'][] = $suhu1['waktu'][$i] ?? $suhu2['waktu'][$i] ?? '00:00:00';
                        $avgSuhuValues[] = $val;
                    }
                    
                    // ... (Logika averaging kelembaban) ...
                    $kelembabanAvg = 0; $kelembabanCount = 0;
                    if ($kelembaban1 && isset($kelembaban1['values'][$i])) { $kelembabanAvg += $kelembaban1['values'][$i]; $kelembabanCount++; }
                    if ($kelembaban2 && isset($kelembaban2['values'][$i])) { $kelembabanAvg += $kelembaban2['values'][$i]; $kelembabanCount++; }
                    if ($kelembabanCount > 0) {
                        $val = $kelembabanAvg / $kelembabanCount;
                        $averagedData['kelembaban']['values'][] = number_format($val, 1);
                        $averagedData['kelembaban']['waktu'][] = $kelembaban1['waktu'][$i] ?? $kelembaban2['waktu'][$i] ?? '00:00:00';
                        $avgKelembabanValues[] = $val;
                    }
                }
                
                // Hitung rata-rata global dari data yang sudah di-average
                if(count($avgSuhuValues) > 0) $globalAverages['suhu'] = number_format(array_sum($avgSuhuValues) / count($avgSuhuValues), 1);
                if(count($avgKelembabanValues) > 0) $globalAverages['kelembaban'] = number_format(array_sum($avgKelembabanValues) / count($avgKelembabanValues), 1);
                
                break;
        }

        return response()->json([
            'status' => true,
            'namaRuangan' => $ruangan->nama_ruangan,
            'tipeRuangan' => $tipeRuangan,
            'dataSensor' => $dataSensorResponse, // Data sensor individual
            'dataWaktuSensor' => $dataWaktuResponse,
            'avg' => $globalAverages, // Rata-rata global (bisa per sensor atau gabungan)
            'averaged_data' => $averagedData, // Data GABUNGAN untuk chart
        ]);
    }

    /**
     * Mengambil riwayat data Blower (khusus Ruang Pengeringan)
     */
    public function getDataBlowerHistory(string $id, string $tgl)
    {
        $ruangan = RuanganModel::findOrFail($id);
        
        // Pastikan ini adalah ruang pengeringan
        if($ruangan->tipe_ruangan != 3) {
            return response()->json(['status' => false, 'msg' => 'Blower hanya ada di Ruang Pengeringan']);
        }
        
        $dataBlower = [];
        $dataWaktuBlower = [];
        $sensorBlower = null;

        foreach($ruangan->getDataSensor as $sensor) {
            if($sensor->flag_sensor == 'blower') {
                $sensorBlower = $sensor;
                break;
            }
        }

        if(!$sensorBlower) {
            return response()->json(['status' => false, 'msg' => 'Sensor Blower tidak ditemukan']);
        }

        // Ambil log blower pada tanggal yang diminta
        $logs = $sensorBlower->getDataLogModeBlower()
            ->whereDate('created_at', $tgl)
            ->orderBy('created_at', 'asc') // Urutkan dari pagi
            ->get();

        if($logs->isEmpty()) {
             return response()->json([
                'status' => false,
                'msg' => 'Data Blower pada tanggal ' . $tgl . ' tidak ditemukan!',
            ]);
        }
        
        foreach($logs as $log) {
            $dataBlower[] = $log->nilai_sensor;
            $dataWaktuBlower[] = date('G:i:s', $log->created_at->timestamp);
        }

        // Hitung durasi aktif
        $durasiAktifDetik = 0;
        $lastOnTime = null;

        foreach ($dataBlower as $i => $nilai) {
            $currentTime = strtotime($dataWaktuBlower[$i]);
            if ($nilai == 1) {
                if ($lastOnTime === null) {
                    $lastOnTime = $currentTime;
                }
            } elseif ($nilai == 0 && $lastOnTime) {
                $durasiAktifDetik += ($currentTime - $lastOnTime);
                $lastOnTime = null;
            }
        }
        
        // Jika log terakhir 'ON' dan tidak pernah 'OFF' di hari itu
        if ($lastOnTime) {
            // Kita hitung sampai akhir hari itu
            $endOfDay = strtotime($tgl . ' 23:59:59');
            $durasiAktifDetik += ($endOfDay - $lastOnTime);
        }

        return response()->json([
            'status' => true,
            'durasiAktifMenit' => round($durasiAktifDetik / 60),
            'dataBlower' => $dataBlower,
            'dataWaktuBlower' => $dataWaktuBlower,
        ]);
    }
}