<?php

namespace App\Http\Controllers;

use App\Models\GudangModel;
use App\Models\NilaiSensorModel;
use App\Models\SensorModel;
use Illuminate\Http\Request;

class RuanganFermentasiController extends Controller
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
                        foreach($value2->getDataNilaiSensor()->orderBy('created_at', 'desc')->limit(15)->get() as $value3) {
                            $dataSuhu[] = $value3->nilai_sensor;
                            $dataWaktuSuhu[] = date('G:i:s', $value3->created_at->timestamp);
                        }
                    } else if($value2->flag_sensor == 'kelembaban') {
                        foreach($value2->getDataNilaiSensor()->orderBy('created_at', 'desc')->limit(15)->get() as $value3) {
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

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.fermentasi.index');
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
