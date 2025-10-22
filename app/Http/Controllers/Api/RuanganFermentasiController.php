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
        $dataSensor = [];
        $dataWaktuSensor = [];
        $dataGudang = GudangModel::findOrFail($id);
        $dataRuangan = $dataGudang->getDataRuangan;
        $nilaiSensorTemp = [];
        $waktuSensorTemp = [];
        $statusRuangan = 1;

        foreach ($dataRuangan as $value) { 
            if($value->tipe_ruangan == 2) {
                $statusRuangan = $value->status_ruangan;
                foreach($value->getDataSensor as $value2) {
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
