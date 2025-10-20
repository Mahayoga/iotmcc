<?php

namespace App\Http\Controllers;

use App\Models\RuanganModel;
use Illuminate\Http\Request;
use App\Models\GudangModel;
use App\Models\NilaiSensorModel;
use App\Models\SensorModel;


class RiwayatDataController extends Controller
{     
   
   public function getDataSensor(string $id) {
      $dataSuhu = [];
        $dataWaktuSuhu = [];
        $dataKelembaban = [];
        $dataWaktuKelembaban = [];
        $statusRuangan = 1;
        $dataGudang = GudangModel::findOrFail($id);

        $dataRuangan = $dataGudang->getDataRuangan;

        foreach ($dataRuangan as $value) { 
            if($value->tipe_ruangan == 2) {
                $statusRuangan = $value->status_ruangan;
                foreach($value->getDataSensor as $value2) {
                    if($value2->flag_sensor == 'suhu') {
                        foreach($value2->getDataNilaiSensor()->orderBy('created_at', 'desc')->limit(5)->get() as $value3) {
                            $dataSuhu[] = $value3->nilai_sensor;
                            $dataWaktuSuhu[] = date('G:i:s', $value3->created_at->timestamp);
                        }
                    } else if($value2->flag_sensor == 'kelembaban') {
                        foreach($value2->getDataNilaiSensor()->orderBy('created_at', 'desc')->limit(5)->get() as $value3) {
                            $dataKelembaban[] = $value3->nilai_sensor;
                            $dataWaktuKelembaban[] = date('G:i:s', $value3->created_at->timestamp);
                        }
                    }
                }
            }
        }

        return response()->json([
            'status' => true,
            'dataSuhu' => $dataSuhu,
            'dataKelembaban' => $dataKelembaban,
            'dataWaktuSuhu' => $dataWaktuSuhu,
            'dataWaktuKelembaban' => $dataWaktuKelembaban,
            'dataAvgSuhu' => number_format(array_sum($dataSuhu) / count($dataSuhu), 1),
            'dataAvgKelembaban' => number_format(array_sum($dataKelembaban) / count($dataKelembaban), 1),
            // 'statusRuangan' => $idTemp,
        ]);
   }


   public function index() {
    $gudang = GudangModel::all(); 
    $ruangan = RuanganModel::all(); 
    return view('admin.riwayat.index', compact('gudang', 'ruangan'));
   }

   public function getRuangan($idGudang)
   {
    $ruangan = RuanganModel::where('id_gudang', $idGudang)->get();
    return response()->json($ruangan);
   }
   
}
