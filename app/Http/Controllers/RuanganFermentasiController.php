<?php

namespace App\Http\Controllers;

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

        foreach ($dataRuangan as $value) { 
            if($value->tipe_ruangan == 2) {
                $statusRuangan = $value->status_ruangan;
                foreach($value->getDataSensor as $value2) {
                    foreach($value2->getDataNilaiSensor()->orderBy('created_at', 'desc')->limit(11)->get() as $value3) {
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
