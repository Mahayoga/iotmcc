<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GudangModel;
use App\Models\NilaiSensorModel;
use App\Models\SensorModel;
use Carbon\Carbon;

class AlatBleachingController extends Controller
{
    public function getDataSensor(string $id)
    {
        $dataGudang = GudangModel::findOrFail($id);
        $dataRuangan = $dataGudang->getDataRuangan;

        $avgSuhu = 0;
        $nilaiSensorGraph = [];
        $waktuSensorGraph = [];
        $targetDate = '2025-10-26';
         
        foreach ($dataRuangan as $value) {
            if ($value->tipe_ruangan == 1) {
                $sensorSuhu = $value->getDataSensor->firstWhere('flag_sensor', 'suhu_1');
                if ($sensorSuhu) {
                    $avgSuhu = $sensorSuhu->getDataNilaiSensor()
                                         ->whereDate('created_at', $targetDate)
                                         ->whereTime('created_at', '>=', '07:00:00')
                                         ->whereTime('created_at', '<=', '10:00:00')
                                         ->avg('nilai_sensor');

                    $graphData = $sensorSuhu->getDataNilaiSensor()
                                           ->whereDate('created_at', $targetDate)
                                           ->orderBy('created_at', 'asc')
                                           ->get();

                    foreach ($graphData as $dataPoint) {
                        $nilaiSensorGraph[] = $dataPoint->nilai_sensor;
                        $waktuSensorGraph[] = Carbon::parse($dataPoint->created_at)->format('G:i:s');
                    }
                    
                    break; 
                }
            }
        }

        return response()->json([
            'status' => true,
            'rataRataSuhu_7_10' => number_format($avgSuhu, 2), 
            'graphSuhu' => $nilaiSensorGraph,                
            'graphWaktu' => $waktuSensorGraph,               
        ]);
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
