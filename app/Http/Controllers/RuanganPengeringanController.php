<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GudangModel;
use App\Models\NilaiSensorModel;
use App\Models\SensorModel;
use App\Models\ModeBlowerModel;
use App\Models\LogModeBlowerModel;
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

    $currentSuhu = 0;
    $currentKelembaban = 0;
    $countSuhu = 0;
    $countKelembaban = 0;

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
        foreach ($value->getDataSensor as $value2) {
          if (str_contains($value2->flag_sensor, "blower")) {
            continue;
          }
          $dateNow = '%' . date("Y-m-d") . '%';
          if ($value2->getDataNilaiSensor()->where('created_at', 'LIKE', $dateNow)->count() == 0) {
            $temp = $value2->getDataNilaiSensor()->orderBy('created_at', 'DESC')->limit(1)->first();
            if ($temp) {
              $dateNow = '%' . $temp->created_at->format('Y-m-d') . '%';
            }
          }

          $records = $value2->getDataNilaiSensor()
            ->selectRaw($selectRawQuery)
            ->where('created_at', 'LIKE', $dateNow)
            ->groupBy('tgl', 'jam', 'menit_group')
            ->orderBy('waktu_asli', 'DESC')
            ->limit($this->LIMIT)
            ->get();

          foreach ($records as $r) {
            $nilaiSensorTemp[] = number_format($r->avg_nilai, 2);
            $waktuSensorTemp[] = date('G:i', strtotime($r->waktu_asli));
            $stddevTemp[] = [strtotime($r->waktu_asli) * 1000, number_format($r->stddev, 2)];
          }

          $latest = $value2->getDataNilaiSensor()
            ->where('created_at', 'LIKE', $dateNow)
            ->orderBy('created_at', 'DESC')
            ->first();

          if ($latest) {
            if (str_contains($value2->flag_sensor, 'suhu')) {
              $currentSuhu += (float) $latest->nilai_sensor;
              $countSuhu++;
            } else if (str_contains($value2->flag_sensor, 'kelembaban')) {
              $currentKelembaban += (float) $latest->nilai_sensor;
              $countKelembaban++;
            }
          }

          array_push($dataSensor, [
            'type' => 'sensor',
            'flag_sensor' => $value2->flag_sensor,
            'value' => $nilaiSensorTemp,
            'avg' => count($nilaiSensorTemp) > 0
              ? number_format(array_sum($nilaiSensorTemp) / count($nilaiSensorTemp), 1)
              : 0,
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
      'currentSuhu' => $countSuhu > 0 ? number_format($currentSuhu / $countSuhu, 2) : 0,
      'currentKelembaban' => $countKelembaban > 0 ? number_format($currentKelembaban / $countKelembaban, 2) : 0,
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
