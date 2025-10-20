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
        $statusRuangan = 1;  
        $dataGudang = GudangModel::findOrFail($id);
        $dataRuangan = $dataGudang->getDataRuangan;
        $idSuhuTemp = [];
        $nilaiSuhuTemp = [];
        $waktuSuhuTemp = [];

        foreach ($dataRuangan as $value) { 
            if($value->tipe_ruangan == 1) {
                $statusRuangan = $value->status_ruangan;
                foreach($value->getDataSensor as $value2) {
                    if($value2->flag_sensor == 'suhu') {
                        if(!in_array($value2->id_sensor, $idSuhuTemp)) {
                            $idSuhuTemp[] = $value2->id_sensor;
                            foreach($value2->getDataNilaiSensor()->orderBy('created_at', 'desc')->limit(11)->get() as $value3) {
                                $nilaiSuhuTemp[] = $value3->nilai_sensor;
                                $waktuSuhuTemp[] = date('G:i:s', $value3->created_at->timestamp);
                            }
                            array_push($dataSuhu, $nilaiSuhuTemp);
                            array_push($dataWaktuSuhu, $waktuSuhuTemp);
                            $nilaiSuhuTemp = [];
                            $waktuSuhuTemp = [];
                        }
                    } 
    
                }
            }
        }

        $jumlahBarisSuhu = count($dataSuhu);
        $jumlahKolomSuhu = count($dataSuhu[0]);
        $rataRataKolomSuhu = [];

        for ($i = 0; $i < $jumlahKolomSuhu; $i++) {
            $total = 0;
            for ($j = 0; $j < $jumlahBarisSuhu; $j++) {
                $total += $dataSuhu[$j][$i];
            }
            $rataRataKolomSuhu[$i] = number_format($total / $jumlahBarisSuhu, 1);
        }

        return response()->json([
            'status' => true,
            'dataSuhu' => $dataSuhu,
            'dataWaktuSuhu' => $dataWaktuSuhu,
            'dataAvgSuhu' => $rataRataKolomSuhu,
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
