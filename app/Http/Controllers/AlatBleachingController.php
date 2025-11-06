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

    public function setLimitTimer(Request $request, string $id)
    {
        $request->validate([
            'limit_timer' => 'required|integer|min:1',
            'flag_sensor' => 'required|string|in:timer_1,timer_2'
        ]);

        try {
            $dataGudang = GudangModel::findOrFail($id);
            $dataRuangan = $dataGudang->getDataRuangan;

            foreach ($dataRuangan as $ruangan) {
                if ($ruangan->tipe_ruangan == 1) {
                    foreach ($ruangan->getDataSensor as $sensor) {
                        if ($sensor->flag_sensor === $request->flag_sensor) {
                            ModeTimerModel::updateOrCreate(
                                ['id_sensor' => $sensor->id_sensor],
                                ['limit_timer' => $request->limit_timer * 60] 
                            );

                            NilaiTimerModel::create([
                                'flag_timer' => $request->flag_sensor,
                                'nilai_timer' => 0,
                                'id_sensor' => $sensor->id_sensor,
                                'rssi' => 0,
                                'snr' => 0,
                            ]);

                            return response()->json([
                                'status' => true,
                                'message' => 'Timer berhasil diset'
                            ]);
                        }
                    }
                }
            }

            return response()->json([
                'status' => false,
                'message' => 'Sensor tidak ditemukan'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal set timer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("admin.bleaching.index");
    }
   
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
