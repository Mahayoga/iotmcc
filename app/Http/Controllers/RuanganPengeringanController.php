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
  public function getDataSensor(string $id)
  {
    $dataSensor = [];
    $dataWaktuSensor = [];
    $dataGudang = GudangModel::findOrFail($id);
    $dataRuangan = $dataGudang->getDataRuangan;
    $nilaiSensorTemp = [];
    $waktuSensorTemp = [];

    foreach ($dataRuangan as $value) {
      if ($value->tipe_ruangan == 3) {
        $statusRuangan = $value->status_ruangan;
        foreach ($value->getDataSensor as $value2) {
          if (!str_contains($value2->flag_sensor, "blower")) {
            foreach ($value2->getDataNilaiSensor()->orderBy('created_at', 'desc')->limit(11)->get() as $value3) {
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


  public function getDataStatusBlower(string $id)
  {
    try {
      $sensor = SensorModel::with('getDataNilaiBlower')->findOrFail($id);

      if (!$sensor->getDataNilaiBlower) {
        return response()->json([
          'status' => false,
          'msg' => 'Data blower tidak ditemukan'
        ], 404);
      }

      return response()->json([
        'status' => true,
        'data' => [
          'id_sensor' => $sensor->id_sensor,
          'nilai_sensor' => $sensor->getDataNilaiBlower->nilai_sensor,
          'is_active' => $sensor->getDataNilaiBlower->nilai_sensor == '1'
        ]
      ]);

    } catch (\Exception $e) {
      return response()->json([
        'status' => false,
        'msg' => 'Terjadi kesalahan saat mengambil data',
        'error' => $e->getMessage()
      ], 500);
    }
  }

  // public function showBlower()
  // {
  //   return view('ruang.blower', [
  //     'blowerSensors' => [
  //       1 => '4519cc50-56ae-4e94-90b0-b17f2c5b4c15',
  //       2 => '4519cc50-56ae-4e94-90b0-b17f2c5b4c16'
  //     ]
  //   ]);
  // }

  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    // Ambil semua blower dari tabel mode_blower beserta sensor terkait
    $blower = ModeBlowerModel::with('getDataSensor')->get()
      ->keyBy(function ($item) {
        // Ambil nomor blower dari flag_sensor (misal: "blower-1")
        preg_match('/\d+/', $item->getDataSensor->flag_sensor ?? '', $matches);
        return $matches[0] ?? null;
      });

    // Membuat mapping: nomor blower => id_sensor (UUID)
    $blowerSensors = [];
    foreach ($blower as $index => $item) {
      if ($index !== null) {
        $blowerSensors[(int) $index] = $item->id_sensor;
      }
    }

    return view('admin.pengeringan.index', compact('blower', 'blowerSensors'));
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
