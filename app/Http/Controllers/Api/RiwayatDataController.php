<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\RuanganModel;
use Illuminate\Http\Request;

class RiwayatDataController extends Controller
{
    public function getDataSensor(string $id, string $tgl) {
        $dataSensor = [];
        $dataWaktuSensor = [];
        $dataRuangan = RuanganModel::findOrFail($id);
        $nilaiSensorTemp = [];
        $waktuSensorTemp = [];
        $statusRuangan = 1;

        foreach($dataRuangan->getDataSensor as $value2) {
            if(!str_contains($value2->flag_sensor, 'timer') && !str_contains($value2->flag_sensor,'blower')) {
                if(count($value2->getDataNilaiSensor()->where('created_at', 'LIKE', '%' . $tgl . '%')->get()) <= 0) {
                    return response()->json([
                        'status' => false,
                        'msg' => 'Data pada tanggal ini tidak ditemukan!',
                        'tgl' => $tgl

                    ]);
                }
                foreach($value2->getDataNilaiSensor()->where('created_at', 'LIKE', '%' . $tgl . '%')->orderBy('created_at', 'desc')->get() as $value3) {
                    $nilaiSensorTemp[] = $value3->nilai_sensor;
                    $waktuSensorTemp[] = date('G:i:s', $value3->created_at->timestamp);
                }
                array_push($dataSensor, [
                    'type' => 'sensor',
                    'flag_sensor' => $value2->flag_sensor,
                    'value' => $nilaiSensorTemp,
                    'avg' => number_format(array_sum($nilaiSensorTemp) / count($nilaiSensorTemp), 1),
                    'time_label' => $waktuSensorTemp
                ]);
                // array_push($dataWaktuSensor, [
                //     'type' => 'waktu',
                //     'flag_sensor' => $value2->flag_sensor,
                //     'value' => $waktuSensorTemp
                // ]);
                $nilaiSensorTemp = [];
                $waktuSensorTemp = [];
            }
        }

        return response()->json([
            'status' => true,
            'dataSensor' => $dataSensor,
            'dataWaktuSensor' => $dataWaktuSensor,
            'namaRuangan' => $dataRuangan->nama_ruangan,
            'statusRuangan' => $statusRuangan,
        ]);
   }

    public function getRuangan($idGudang)
   {
    $ruangan = RuanganModel::where('id_gudang', $idGudang)->get();
    return response()->json($ruangan);
   }
}
