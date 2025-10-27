<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GudangModel;
use App\Models\NilaiSensorModel;
use App\Models\SensorModel;

class RuanganBlanchingController extends Controller
{

        public function getDataSensor(string $id) {
        $dataSensor = [];
        $dataWaktuSensor = [];
        $dataGudang = GudangModel::findOrFail($id);
        $dataRuangan = $dataGudang->getDataRuangan;
        $nilaiSensorTemp = [];
        $waktuSensorTemp = [];
        $statusRuangan = 1;
        // $i = 0;

        foreach ($dataRuangan as $value) { 
            if($value->tipe_ruangan == 1) { // Asumsi 1 adalah Blanching/Perebusan
                $statusRuangan = $value->status_ruangan;
                foreach($value->getDataSensor as $value2) {
                    if(str_contains($value2->flag_sensor, 'timer')) {
                        break;
                    }

                    // Array baru untuk menampung nilai sensor pagi
                    $nilaiSensorPagi = []; 

                    foreach($value2->getDataNilaiSensor()->orderBy('created_at', 'desc')->limit(288)->get() as $value3) {
                        $nilai = $value3->nilai_sensor;
                        $timestamp = $value3->created_at->timestamp;
                        $waktu = date('G:i:s', $timestamp);
                        $jam = (int)date('G', $timestamp); // Ambil jam dalam format 0-23

                        // 1. Selalu tambahkan ke data sensor utama (24 jam)
                        $nilaiSensorTemp[] = $nilai;
                        $waktuSensorTemp[] = $waktu;

                        // 2. Cek kondisi jam (7, 8, 9). 
                        // (jam >= 7 DAN jam < 10) berarti dari 07:00:00 s/d 09:59:59
                        if ($jam >= 7 && $jam < 10) {
                            $nilaiSensorPagi[] = $nilai;
                        }
                    }

                    // --- Kalkulasi Rata-rata ---

                    // 1. Hitung avg global (24 jam)
                    $avgGlobal = 0;
                    if (count($nilaiSensorTemp) > 0) {
                        $avgGlobal = number_format(array_sum($nilaiSensorTemp) / count($nilaiSensorTemp), 1);
                    }

                    // 2. Hitung avg pagi (7-10)
                    $avgPagi = 0;
                    if (count($nilaiSensorPagi) > 0) {
                        $avgPagi = number_format(array_sum($nilaiSensorPagi) / count($nilaiSensorPagi), 1);
                    }

                    // --- Dorong data ke array response ---
                    array_push($dataSensor, [
                        'type' => 'sensor',
                        'flag_sensor' => $value2->flag_sensor,
                        'value' => $nilaiSensorTemp,
                        'avg' => $avgGlobal, // Rata-rata 24 jam
                        'avg_pagi' => $avgPagi, // Rata-rata BARU jam 7-10
                    ]);
                    
                    array_push($dataWaktuSensor, [
                        'type' => 'waktu',
                        'flag_sensor' => $value2->flag_sensor,
                        'value' => $waktuSensorTemp
                    ]);

                    // Reset array sementara
                    $nilaiSensorTemp = [];
                    $waktuSensorTemp = [];
                    // $nilaiSensorPagi akan otomatis di-reset di iterasi berikutnya
                }
            }
        }

        return response()->json([
            'status' => true,
            'dataSensor' => $dataSensor,
            'dataWaktuSensor' => $dataWaktuSensor,
            'statusRuangan' => $statusRuangan,
        ]);

    }
}
