<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GudangModel;
use App\Models\NilaiSensorModel;
use App\Models\SensorModel;
use App\Models\ModeBlowerModel;
use App\Models\LogModeBlowerModel;

class RuanganPengeringanController extends Controller
{
  public function getDataSensor(string $id) {
        $dataSensor = [];
        $dataWaktuSensor = [];
        $dataGudang = GudangModel::findOrFail($id);
        $dataRuangan = $dataGudang->getDataRuangan;
        $nilaiSensorTemp = [];
        $waktuSensorTemp = [];

        foreach ($dataRuangan as $value) { 
            if($value->tipe_ruangan == 3) {
              $statusRuangan = $value->status_ruangan;
                foreach($value->getDataSensor as $value2) {
                  if(!str_contains($value2->flag_sensor,"blower")) {
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
        }

        return response()->json([
            'status' => true,
            'dataSensor' => $dataSensor,
            'dataWaktuSensor' => $dataWaktuSensor,
        ]);

    }


  public function getDataBlower(string $id)
  {
    $dataBlower = [];
    $dataWaktuBlower = [];
    $statusRuangan = 1; // default

    $dataGudang = GudangModel::findOrFail($id);
    $dataRuangan = $dataGudang->getDataRuangan;

    foreach ($dataRuangan as $ruangan) {
      if ($ruangan->tipe_ruangan == 3) {
        $statusRuangan = $ruangan->status_ruangan;

        foreach ($ruangan->getDataSensor as $sensor) {
          if ($sensor->flag_sensor == 'blower') {
            $logs = $sensor->getDataLogModeBlower()->orderBy('created_at', 'desc')->limit(30)->get();

            foreach ($logs as $log) {
              $dataBlower[] = $log->nilai_sensor;
              $dataWaktuBlower[] = date('G:i:s', $log->created_at->timestamp);
            }
          }
        }
      }
    }

    $durasiAktif = 0;
    $lastOnTime = null;
    foreach (array_reverse($dataBlower) as $i => $nilai) {
      $time = $dataWaktuBlower[$i];
      if ($nilai == 1) {
        $lastOnTime = $time;
      } elseif ($nilai == 0 && $lastOnTime) {
        $durasiAktif += (strtotime($time) - strtotime($lastOnTime)) / 60;
        $lastOnTime = null;
      }
    }
    if ($lastOnTime) {
      $durasiAktif += (time() - strtotime($lastOnTime)) / 60;
    }

    $statusBlower = end($dataBlower) ?? 0;

    return response()->json([
      'status' => true,
      'statusRuangan' => $statusRuangan,
      'statusBlower' => $statusBlower,
      'durasiAktif' => round($durasiAktif),
      'dataBlower' => $dataBlower,
      'dataWaktuBlower' => $dataWaktuBlower,
    ]);
  }


  public function toggleBlower(string $id)
  {
    $dataGudang = GudangModel::findOrFail($id);
    $dataRuangan = $dataGudang->getDataRuangan;

    foreach ($dataRuangan as $ruangan) {
      if ($ruangan->tipe_ruangan == 3) {
        foreach ($ruangan->getDataSensor as $sensor) {
          if ($sensor->flag_sensor == 'blower') {
            $mode = $sensor->getDataModeBlower()->latest()->first();

            if (!$mode) {
              $mode = $sensor->getDataModeBlower()->create(['nilai_sensor' => 0]);
            }

            $mode->nilai_sensor = $mode->nilai_sensor == 1 ? 0 : 1;
            $mode->save();

            $sensor->getDataLogModeBlower()->create([
              'nilai_sensor' => $mode->nilai_sensor,
              'created_at' => now()
            ]);

            return response()->json([
              'status' => true,
              'message' => 'Status blower diperbarui',
              'statusBlower' => $mode->nilai_sensor
            ]);
          }
        }
      }
    }

    return response()->json(['status' => false, 'message' => 'Sensor blower tidak ditemukan']);
  }




  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    return view("admin.pengeringan.index");
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
