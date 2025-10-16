<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RuanganModel;
use App\Models\SensorModel;
use App\Models\NilaiSensorModel;


class RuangPerebusanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
{

    $ruangan = RuanganModel::where('nama_ruangan', 'Ruangan Perebusan')->first();
    
    if (!$ruangan) {
        return back()->with('error', 'Data ruang perebusan tidak ditemukan.');
    }

    $sensorSuhu = SensorModel::where('id_ruangan', $ruangan->id_ruangan)
        ->where('flag_sensor', 'suhu')
        ->first();

    if (!$sensorSuhu) {
        return back()->with('error', 'Sensor suhu belum terdaftar.');
    }

    $nilaiSensor = NilaiSensorModel::where('id_sensor', $sensorSuhu->id_sensor)->first();

    if (!$nilaiSensor) {
        return back()->with('error', 'Nilai sensor belum tersedia.');
    }

    $dataSuhu = \App\Models\LogSensorModel::where('id_nilai_sensor', $nilaiSensor->id_nilai_sensor)
        ->orderBy('created_at', 'desc')
        ->take(20)
        ->get(['nilai_sensor', 'created_at']);

    $timer = 120;
    $startTime = $dataSuhu->last()->created_at ?? now();
    $endTime = $dataSuhu->first()->created_at ?? now();
    $status = 'Sedang Berjalan'; 
    return view('admin.ruang-perebusan.index', compact('ruangan', 'dataSuhu', 'timer', 'startTime', 'endTime', 'status'));
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
