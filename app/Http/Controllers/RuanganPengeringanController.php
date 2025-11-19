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

  /**
   * Display a listing of the resource.
   */
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
