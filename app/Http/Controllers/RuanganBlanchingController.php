<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GudangModel;
use App\Models\NilaiSensorModel;
use App\Models\SensorModel;


class RuanganBlanchingController extends Controller
{
     public function getDataSensor(string $id)
    {
        $dataSuhu = [];
        $dataWaktuSuhu = [];
        $dataKelembaban = [];
        $dataWaktuKelembaban = [];
        $statusRuangan = 1;
        $timerValue = null;

        $dataGudang = GudangModel::findOrFail($id);
        $dataRuangan = $dataGudang->getDataRuangan;

        foreach ($dataRuangan as $value) {
            if ($value->tipe_ruangan == 1) {
                $statusRuangan = $value->status_ruangan;

                foreach ($value->getDataSensor as $value2) {
                    if ($value2->flag_sensor == 'suhu') {
                        foreach ($value2->getDataNilaiSensor()->orderBy('created_at', 'desc')->limit(30)->get() as $value3) {
                            $dataSuhu[] = $value3->nilai_sensor;
                            $dataWaktuSuhu[] = date('G:i:s', $value3->created_at->timestamp);
                        }
                    } elseif ($value2->flag_sensor == 'kelembaban') {
                        foreach ($value2->getDataNilaiSensor()->orderBy('created_at', 'desc')->limit(30)->get() as $value3) {
                            $dataKelembaban[] = $value3->nilai_sensor;
                            $dataWaktuKelembaban[] = date('G:i:s', $value3->created_at->timestamp);
                        }
                    } elseif ($value2->flag_sensor == 'timer') {
                        $latest = $value2->getDataNilaiSensor()->orderBy('created_at', 'desc')->first();
                        if ($latest) $timerValue = $latest->nilai_sensor;
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
            'dataAvgSuhu' => count($dataSuhu) ? number_format(array_sum($dataSuhu) / count($dataSuhu), 1) : 0,
            'dataAvgKelembaban' => count($dataKelembaban) ? number_format(array_sum($dataKelembaban) / count($dataKelembaban), 1) : 0,
            'dataTimer' => $timerValue,
            'statusRuangan' => $statusRuangan,
        ]);
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("admin.blanching.index");
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
