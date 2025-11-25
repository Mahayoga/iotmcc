<?php

namespace App\Http\Controllers;

use App\Models\GudangModel;
use App\Models\NilaiSensorModel;
use App\Models\SensorModel;
use App\Models\ModeBlowerModel;
use App\Models\LogModeBlowerModel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class RuanganPengeringanController extends Controller
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
    $stddevTemp = [];
    $currentSuhu = null;
    $currentKelembaban = null;

    $selectRawQuery = "
            DATE(created_at) as tgl,
            HOUR(created_at) as jam,
            FLOOR(MINUTE(created_at)/15) as menit_group,
            AVG(nilai_sensor) as avg_nilai,
            MIN(created_at) as waktu_asli,
            STDDEV_SAMP(nilai_sensor) as stddev,
            MIN(nilai_sensor) as min_nilai,
            MAX(nilai_sensor) as max_nilai
        ";

    foreach ($dataRuangan as $value) {
      if ($value->tipe_ruangan == 3) {
        $statusRuangan = $value->status_ruangan;
        foreach ($value->getDataSensor as $value2) {
          if (str_contains($value2->flag_sensor, 'blower')) {
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
            $nilaiSensorTemp[] = number_format($value3->avg_nilai, 2);
            $waktuSensorTemp[] = date('G:i', Carbon::parse($value3->waktu_asli)->timestamp);
            $stddevTemp[] = [Carbon::parse($value3->waktu_asli)->valueOf(), number_format($value3->stddev, 2)];
          }

          foreach ($value2->getDataNilaiSensor()
            ->where('created_at', 'LIKE', $dateNow)
            ->orderBy('created_at', 'DESC')
            ->limit(1)
            ->get() as $value3) {
            if (str_contains($value2->flag_sensor, 'suhu')) {
              $currentSuhu += (int) $value3->nilai_sensor;
            } else if (str_contains($value2->flag_sensor, 'kelembaban')) {
              $currentKelembaban += (int) $value3->nilai_sensor;
            }
          }

          array_push($dataSensor, [
            'type' => 'sensor',
            'flag_sensor' => $value2->flag_sensor,
            'value' => $nilaiSensorTemp,
            'avg' => number_format(array_sum($nilaiSensorTemp) / count($nilaiSensorTemp), 1),
            'stddev' => $stddevTemp,
          ]);

          array_push($dataWaktuSensor, [
            'type' => 'waktu',
            'flag_sensor' => $value2->flag_sensor,
            'value' => $waktuSensorTemp
          ]);

          $nilaiSensorTemp = [];
          $waktuSensorTemp = [];
          $stddevTemp = [];
        }
      }
    }

    return response()->json([
      'status' => true,
      'dataSensor' => $dataSensor,
      'dataWaktuSensor' => $dataWaktuSensor,
      'currentSuhu' => number_format($currentSuhu / 2, 2),
      'currentKelembaban' => number_format($currentKelembaban / 2, 2)
    ]);
  }


  public function getDataStatusBlower(string $id)
  {
    try {
      \Log::info("Getting blower status for sensor ID: " . $id);

      $sensor = SensorModel::with('getDataNilaiBlower')->find($id);

      if (!$sensor) {
        return response()->json([
          'status' => false,
          'msg' => 'Sensor tidak ditemukan'
        ], 404);
      }

      if (!str_contains($sensor->flag_sensor, 'blower')) {
        return response()->json([
          'status' => false,
          'msg' => 'Sensor bukan tipe blower'
        ], 400);
      }

      if (!$sensor->getDataNilaiBlower) {
        \Log::warning("No mode_blower data, creating default for sensor: " . $id);

        ModeBlowerModel::create([
          'id_sensor' => $sensor->id_sensor,
          'nilai_sensor' => '0',
        ]);

        $sensor->load('getDataNilaiBlower');
      }

      return response()->json([
        'status' => true,
        'data' => [
          'id_sensor' => $sensor->id_sensor,
          'flag_sensor' => $sensor->flag_sensor,
          'nilai_sensor' => $sensor->getDataNilaiBlower->nilai_sensor,
          'is_active' => $sensor->getDataNilaiBlower->nilai_sensor == '1',
          'updated_at' => $sensor->getDataNilaiBlower->updated_at->format('Y-m-d H:i:s')
        ]
      ]);

    } catch (\Exception $e) {
      \Log::error("Error in getDataStatusBlower: " . $e->getMessage());

      return response()->json([
        'status' => false,
        'msg' => 'Terjadi kesalahan: ' . $e->getMessage()
      ], 500);
    }
  }

  
  public function updateBlowerStatus(Request $request, string $id)
  {
    try {
      $validated = $request->validate([
        'nilai_sensor' => 'required|in:0,1'
      ]);

      \Log::info("Updating blower {$id} to: " . $validated['nilai_sensor']);


      $sensor = SensorModel::find($id);

      if (!$sensor) {
        return response()->json([
          'status' => false,
          'msg' => 'Sensor tidak ditemukan'
        ], 404);
      }

      if (!str_contains($sensor->flag_sensor, 'blower')) {
        return response()->json([
          'status' => false,
          'msg' => 'Sensor bukan tipe blower'
        ], 400);
      }

      $modeBlower = ModeBlowerModel::updateOrCreate(
        ['id_sensor' => $sensor->id_sensor],
        ['nilai_sensor' => $validated['nilai_sensor']]
      );

      try {
        LogModeBlowerModel::create([
          'id_mode_blower' => $modeBlower->id_mode_blower,
          'nilai_sensor' => $validated['nilai_sensor'],
        ]);
      } catch (\Exception $e) {
        \Log::warning("Failed to log blower change: " . $e->getMessage());
      }

      $statusText = $validated['nilai_sensor'] == '1' ? 'dihidupkan' : 'dimatikan';

      return response()->json([
        'status' => true,
        'msg' => "Blower berhasil {$statusText}",
        'data' => [
          'id_sensor' => $modeBlower->id_sensor,
          'nilai_sensor' => $modeBlower->nilai_sensor,
          'is_active' => $modeBlower->nilai_sensor == '1'
        ]
      ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
      return response()->json([
        'status' => false,
        'msg' => 'Data tidak valid',
        'errors' => $e->errors()
      ], 422);
    } catch (\Exception $e) {
      \Log::error("Error updating blower: " . $e->getMessage());

      return response()->json([
        'status' => false,
        'msg' => 'Gagal mengupdate blower: ' . $e->getMessage()
      ], 500);
    }
  }


  public function getAllBlowersStatus(string $gudangId)
  {
    try {
      $dataGudang = GudangModel::findOrFail($gudangId);
      $dataRuangan = $dataGudang->getDataRuangan;
      $blowersData = [];

      foreach ($dataRuangan as $ruangan) {
        if ($ruangan->tipe_ruangan == 3) { 
          foreach ($ruangan->getDataSensor as $sensor) {
            if (str_contains($sensor->flag_sensor, 'blower')) {
        
              $sensor->load('getDataNilaiBlower');

              preg_match('/\d+/', $sensor->flag_sensor, $matches);
              $blowerNumber = $matches[0] ?? null;

              if (!$sensor->getDataNilaiBlower) {
                ModeBlowerModel::create([
                  'id_sensor' => $sensor->id_sensor,
                  'nilai_sensor' => '0',
                ]);
                $sensor->load('getDataNilaiBlower');
              }

              $blowersData[] = [
                'blower_number' => $blowerNumber,
                'id_sensor' => $sensor->id_sensor,
                'flag_sensor' => $sensor->flag_sensor,
                'nilai_sensor' => $sensor->getDataNilaiBlower->nilai_sensor,
                'is_active' => $sensor->getDataNilaiBlower->nilai_sensor == '1',
                'updated_at' => $sensor->getDataNilaiBlower->updated_at->format('Y-m-d H:i:s')
              ];
            }
          }
        }
      }

      return response()->json([
        'status' => true,
        'data' => $blowersData
      ]);

    } catch (\Exception $e) {
      \Log::error("Error getting all blowers: " . $e->getMessage());

      return response()->json([
        'status' => false,
        'msg' => 'Gagal mengambil data blower'
      ], 500);
    }
  }


  public function index()
  {
    $blower = ModeBlowerModel::with('getDataSensor')->get()
      ->keyBy(function ($item) {
        preg_match('/\d+/', $item->getDataSensor->flag_sensor ?? '', $matches);
        return $matches[0] ?? null;
      });

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
