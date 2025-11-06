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
            if($value->tipe_ruangan == 1) {
                $statusRuangan = $value->status_ruangan;
                foreach($value->getDataSensor as $value2) {
                    if(str_contains($value2->flag_sensor, 'timer')) {
                        break;
                    }

                    $nilaiSensorPagi = [];

                    foreach($value2->getDataNilaiSensor()->orderBy('created_at', 'desc')->limit(288)->get() as $value3) {
                        $nilai = $value3->nilai_sensor;
                        $timestamp = $value3->created_at->timestamp;
                        $waktu = date('G:i:s', $timestamp);
                        $jam = (int)date('G', $timestamp);

                        $nilaiSensorTemp[] = $nilai;
                        $waktuSensorTemp[] = $waktu;

                        if ($jam >= 7 && $jam < 10) {
                            $nilaiSensorPagi[] = $nilai;
                        }
                    }

                    $avgGlobal = 0;
                    if (count($nilaiSensorTemp) > 0) {
                        $avgGlobal = number_format(array_sum($nilaiSensorTemp) / count($nilaiSensorTemp), 1);
                    }

                    $avgPagi = 0;
                    if (count($nilaiSensorPagi) > 0) {
                        $avgPagi = number_format(array_sum($nilaiSensorPagi) / count($nilaiSensorPagi), 1);
                    }

                    array_push($dataSensor, [
                        'type' => 'sensor',
                        'flag_sensor' => $value2->flag_sensor,
                        'value' => $nilaiSensorTemp,
                        'avg' => $avgGlobal,
                        'avg_pagi' => $avgPagi,
                    ]);

                    array_push($dataWaktuSensor, [
                        'type' => 'waktu',
                        'flag_sensor' => $value2->flag_sensor,
                        'value' => $waktuSensorTemp
                    ]);

                    $nilaiSensorTemp = [];
                    $waktuSensorTemp = [];
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
