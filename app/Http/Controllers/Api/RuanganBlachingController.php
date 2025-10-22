<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GudangModel;
use App\Models\NilaiSensorModel;
use App\Models\SensorModel;

class RuanganBlachingController extends Controller
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
                    foreach($value2->getDataNilaiSensor()->orderBy('created_at', 'desc')->limit(30)->get() as $value3) {
                        $nilaiSensorTemp[] = $value3->nilai_sensor;
                        $waktuSensorTemp[] = date('G:i:s', $value3->created_at->timestamp);
                    }
                    array_push($dataSensor, [
                        'type' => 'sensor',
                        'flag_sensor' => $value2->flag_sensor,
                        'value' => $nilaiSensorTemp,
                        'avg' => number_format(array_sum($nilaiSensorTemp) / count($nilaiSensorTemp), 1),
                    ]);
                    array_push($dataWaktuSensor, [
                        'type' => 'waktu',
                        'flag_sensor' => $value2->flag_sensor,
                        'value' => $waktuSensorTemp
                    ]);
                    $nilaiSensorTemp = [];
                    $waktuSensorTemp = [];
                    // $i++;
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
