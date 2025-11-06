<?php

namespace App\Http\Controllers;

use App\Models\GudangModel;
use App\Models\NilaiSensorModel;
use App\Models\NilaiTimerModel;
use App\Models\ModeTimerModel;
use App\Models\SensorModel;
use Illuminate\Http\Request;

class AlatBleachingController extends Controller
{
    public function getDataSensor(string $id)
    {
        $dataSensor = [];
        $dataWaktuSensor = [];
        $dataGudang = GudangModel::findOrFail($id);
        $dataRuangan = $dataGudang->getDataRuangan;
        $nilaiSensorTemp = [];
        $waktuSensorTemp = [];

        foreach ($dataRuangan as $value) {
            if ($value->tipe_ruangan == 1) {
                $statusRuangan = $value->status_ruangan;
                foreach ($value->getDataSensor as $value2) {
                    if ($value2->flag_sensor == "timer_1") {
                        break;
                    }
                    foreach ($value2->getDataNilaiSensor()->orderBy('created_at', 'desc')->limit(11)->get() as $value3) {
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
        ]);

    }

    public function getDataTimer(string $id)
    {
        $dataGudang = GudangModel::findOrFail($id);
        $dataRuangan = $dataGudang->getDataRuangan;
        $dataTimer = [];

        foreach ($dataRuangan as $ruangan) {
            if ($ruangan->tipe_ruangan == 1) {
                foreach ($ruangan->getDataSensor as $sensor) {
                    if (in_array($sensor->flag_sensor, ['timer_1', 'timer_2'])) {
                        $nilaiTimer = NilaiTimerModel::where('id_sensor', $sensor->id_sensor)
                            ->orderBy('created_at', 'desc')->first();

                        $modeTimer = ModeTimerModel::where('id_sensor', $sensor->id_sensor)->first();

                        $dataTimer[] = [
                            'flag_timer' => $sensor->flag_sensor,
                            'nilai_timer' => $nilaiTimer?->nilai_timer ?? 0,
                            'limit_timer' => $modeTimer?->limit_timer ?? null,
                            'updated_at' => $nilaiTimer?->created_at?->format('Y-m-d H:i:s'),
                        ];
                    }
                }
            }
        }

        return response()->json([
            'status' => true,
            'dataTimer' => $dataTimer,
        ]);
    }

     public function index()
    {
        return view("admin.bleaching.index");
    }
}
