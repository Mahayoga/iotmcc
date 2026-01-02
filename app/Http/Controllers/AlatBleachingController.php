<?php

namespace App\Http\Controllers;

use App\Models\GudangModel;
use App\Models\NilaiSensorModel;
use App\Models\NilaiTimerModel;
use App\Models\ModeTimerModel;
use App\Models\SensorModel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AlatBleachingController extends Controller
{
    public $LIMIT = 50;

    public function getDataSensor(string $id)
    {
        $dataSensor = [];
        $dataWaktuSensor = [];
        $dataGudang = GudangModel::findOrFail($id);
        $dataRuangan = $dataGudang->getDataRuangan;
        $nilaiSensorTemp = [];
        $waktuSensorTemp = [];

        $selectRawQuery = "
            DATE(created_at) AS tgl,
            HOUR(created_at) AS jam,
            FLOOR((MINUTE(created_at) * 60 + SECOND(created_at)) / 30) AS detik_group,
            AVG(nilai_sensor) AS avg_nilai,
            MIN(created_at) AS waktu_asli,
            STDDEV_SAMP(nilai_sensor) AS stddev,
            MIN(nilai_sensor) AS min_nilai,
            MAX(nilai_sensor) AS max_nilai
        ";

        $currentSuhu = null;

        foreach ($dataRuangan as $value) {
            if ($value->tipe_ruangan == 1) {
                $statusRuangan = $value->status_ruangan;
                foreach ($value->getDataSensor as $value2) {
                    if (str_contains($value2->flag_sensor, 'timer')) {
                        continue;
                    }
                    $dateNow = '%2025-12-23%';
                    $isEmpty = false;
                    if ($value2->getDataNilaiSensor()->where('created_at', 'LIKE', $dateNow)->get()->isEmpty()) {
                        array_push($dataSensor, [
                            'type' => 'sensor',
                            'flag_sensor' => $value2->flag_sensor,
                            'value' => [],
                            'avg' => 0,
                        ]);
                        array_push($dataWaktuSensor, [
                            'type' => 'waktu',
                            'flag_sensor' => $value2->flag_sensor,
                            'value' => []
                        ]);
                        $isEmpty = true;
                    }

                    if (!$isEmpty) {
                        foreach ($value2->getDataNilaiSensor()
                            ->selectRaw($selectRawQuery)
                            ->where('created_at', 'LIKE', $dateNow)
                            ->groupBy('tgl', 'jam', 'detik_group')
                            ->orderBy('waktu_asli', 'DESC')
                            ->limit($this->LIMIT)
                            ->get() as $value3) {
                            
                            $nilaiSensorTemp[] = (int) number_format($value3->avg_nilai, 2);
                            $waktuSensorTemp[] = date('G:i', Carbon::parse($value3->waktu_asli)->timestamp);
                        }

                        foreach ($value2->getDataNilaiSensor()
                            ->where('created_at', 'LIKE', $dateNow)
                            ->orderBy('created_at', 'DESC')
                            ->limit(1)
                            ->get() as $value3) {
                            
                            if (str_contains($value2->flag_sensor, 'suhu')) {
                                $currentSuhu += (int) $value3->nilai_sensor;
                            }
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
                    }

                    $nilaiSensorTemp = [];
                    $waktuSensorTemp = [];
                }
            }
        }

        return response()->json([
            'status' => true,
            'dataSensor' => $dataSensor,
            'dataWaktuSensor' => $dataWaktuSensor,
            'currentSuhu' => $currentSuhu !== null ? number_format($currentSuhu / 2, 2) : 0,
        ]);
    }

    public function startStopTimer(string $id) {
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

                        if($nilaiTimer->flag_timer == 'start') {
                            $dataTimer = [
                                'status' => true,
                                'status_timer' => 'stop',
                                'sisa_timer' => number_format((float) $nilaiTimer->nilai_timer - microtime(true), 2),
                            ];
                            NilaiTimerModel::create([
                                'flag_timer' => 'stop',
                                'nilai_timer' => microtime(true),
                                'id_sensor' => $sensor->id_sensor,
                                'rssi' => 0,
                                'snr' => 0,
                            ]);
                        } else if($nilaiTimer->flag_timer == 'stop') {
                            NilaiTimerModel::create([
                                'flag_timer' => 'start',
                                'nilai_timer' => microtime(true),
                                'id_sensor' => $sensor->id_sensor,
                                'rssi' => 0,
                                'snr' => 0,
                            ]);
                            $dataTimer = [
                                'status' => true,
                                'status_timer' => 'start',
                                'sisa_timer' => number_format((float) $nilaiTimer->nilai_timer - microtime(true), 2),
                            ];
                        }
                    }
                }
            }
        }

        return response()->json([
            'status' => true,
            'data' => $dataTimer,
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

                        $sisaTimer = microtime(true) - (float) $nilaiTimer->nilai_timer;
                        if($nilaiTimer->flag_timer == 'stop') {
                            $dataTimer[] = [
                                'flag_timer' => $nilaiTimer->flag_timer,
                                'nilai_timer' => $nilaiTimer?->nilai_timer ?? 0,
                                'limit_timer' => $modeTimer?->limit_timer ?? null,
                                'sisa_timer' => 0,
                                'updated_at' => $nilaiTimer?->created_at?->format('Y-m-d H:i:s'),
                            ];
                            return response()->json([
                                'status' => true,
                                'dataTimer' => $dataTimer,
                            ]);
                        }
                        if((float) $sisaTimer > (float) $modeTimer->limit_timer) {
                            $sisaTimer = 0;
                            if($nilaiTimer->flag_timer == 'start') {
                                NilaiTimerModel::create([
                                    'flag_timer' => 'stop',
                                    'nilai_timer' => microtime(true),
                                    'id_sensor' => $sensor->id_sensor,
                                    'rssi' => 0,
                                    'snr' => 0,
                                ]);
                            }
                        }

                        $dataTimer[] = [
                            'flag_timer' => $nilaiTimer->flag_timer,
                            'nilai_timer' => $nilaiTimer?->nilai_timer ?? 0,
                            'limit_timer' => $modeTimer?->limit_timer ?? 0,
                            'sisa_timer' => $sisaTimer,
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

    public function index()
    {
        return view("admin.bleaching.index");
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}