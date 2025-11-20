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
            DATE(created_at) as tgl,
            HOUR(created_at) as jam,
            FLOOR(MINUTE(created_at)/15) as menit_group,
            AVG(nilai_sensor) as avg_nilai,
            MIN(created_at) as waktu_asli,
            MIN(nilai_sensor) as min_nilai,
            MAX(nilai_sensor) as max_nilai
        ";

        $suhuTotal = 0;
        $totalDataSuhu = 0;

        foreach ($dataRuangan as $value) {
            if ($value->tipe_ruangan == 1) {
                $statusRuangan = $value->status_ruangan;
                foreach ($value->getDataSensor as $value2) {
                    if (str_contains($value2->flag_sensor, 'timer')) {
                        continue;
                    }

                    $dateNow = '%' . date("Y-m-d") . '%';
                    if ($value2->getDataNilaiSensor()->where('created_at', 'LIKE', $dateNow)->get()->isEmpty()) {
                        $temp = $value2->getDataNilaiSensor()->orderBy('created_at', 'DESC')->limit(1)->get();
                        if (!$temp->isEmpty()) {
                            $dateNow = '%' . date('Y-m-d', Carbon::parse($temp[0]->created_at)->timestamp) . '%';
                        }
                    }

                    foreach ($value2->getDataNilaiSensor()
                        ->selectRaw($selectRawQuery)
                        ->where('created_at', 'LIKE', $dateNow)
                        ->groupBy('tgl', 'jam', 'menit_group')
                        ->orderBy('waktu_asli', 'DESC')
                        ->limit($this->LIMIT)
                        ->get() as $value3) {

                        $avgNilai = number_format($value3->avg_nilai, 2);
                        $nilaiSensorTemp[] = $avgNilai;
                        $waktuSensorTemp[] = date('G:i', Carbon::parse($value3->waktu_asli)->timestamp);

                        if (str_contains($value2->flag_sensor, 'suhu')) {
                            $suhuTotal += floatval($avgNilai);
                            $totalDataSuhu++;
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

                    $nilaiSensorTemp = [];
                    $waktuSensorTemp = [];
                }
            }
        }

        $rataRataSuhu = $totalDataSuhu > 0 ? number_format($suhuTotal / $totalDataSuhu, 2) : 0;

        return response()->json([
            'status' => true,
            'dataSensor' => $dataSensor,
            'dataWaktuSensor' => $dataWaktuSensor,
            'rataRataSuhu' => $rataRataSuhu,
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
