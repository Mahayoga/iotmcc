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
        $idSuhuTemp = [];
        $nilaiSuhuTemp = [];
        $idKelembabanTemp = [];
        $nilaiKelembabanTemp = [];
        $waktuSuhuTemp = [];
        $waktuKelembabanTemp = [];

        foreach ($dataRuangan as $value) { 
            if($value->tipe_ruangan == 2) {
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
                    } else if($value2->flag_sensor == 'kelembaban') {
                        if(!in_array($value2->id_sensor, $idKelembabanTemp)) {
                            $idKelembabanTemp[] = $value2->id_sensor;
                            foreach($value2->getDataNilaiSensor()->orderBy('created_at', 'desc')->limit(11)->get() as $value3) {
                                $nilaiKelembabanTemp[] = $value3->nilai_sensor;
                                $waktuKelembabanTemp[] = date('G:i:s', $value3->created_at->timestamp);
                            }
                            array_push($dataKelembaban, $nilaiKelembabanTemp);
                            array_push($dataWaktuKelembaban, $waktuKelembabanTemp);
                            $nilaiKelembabanTemp = [];
                            $waktuKelembabanTemp = [];
                        }
                    }
                }
            }
        }

        $jumlahBarisSuhu = count($dataSuhu);
        $jumlahKolomSuhu = count($dataSuhu[0]);
        $rataRataKolomSuhu = [];

        $jumlahBarisKelembaban = count($dataKelembaban);
        $jumlahKolomKelembaban = count($dataKelembaban[0]);
        $rataRataKolomKelembaban = [];

        for ($i = 0; $i < $jumlahKolomSuhu; $i++) {
            $total = 0;
            for ($j = 0; $j < $jumlahBarisSuhu; $j++) {
                $total += $dataSuhu[$j][$i];
            }
            $rataRataKolomSuhu[$i] = number_format($total / $jumlahBarisSuhu, 1);
        }

        for ($i = 0; $i < $jumlahKolomKelembaban; $i++) {
            $total = 0;
            for ($j = 0; $j < $jumlahBarisKelembaban; $j++) {
                $total += $dataKelembaban[$j][$i];
            }
            $rataRataKolomKelembaban[$i] = number_format($total / $jumlahBarisKelembaban, 1);
        }

        return response()->json([
            'status' => true,
            'dataSuhu' => $dataSuhu,
            'dataKelembaban' => $dataKelembaban,
            'dataWaktuSuhu' => $dataWaktuSuhu,
            'dataWaktuKelembaban' => $dataWaktuKelembaban,
            'dataAvgSuhu' => $rataRataKolomSuhu,
            'dataAvgKelembaban' => $rataRataKolomKelembaban,
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
