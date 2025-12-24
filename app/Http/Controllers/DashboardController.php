<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GudangModel;
use App\Models\RuanganModel;
use App\Models\SensorModel;
use App\Models\NilaiSensorModel;
use App\Models\ModeBlowerModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
  public function index()
  {
    $gudang = GudangModel::with('getDataRuangan.getDataSensor')->get();
    $ruangan = RuanganModel::with('getDataSensor')->get();

    $dataRuangan = [];
    $grafikSuhu = [];
    $grafikKelembapan = [];
    $grafikBleaching = [];
    $trendBleaching = [];
    $trendFermentasiSuhu = [];
    $trendFermentasiKelembapan = [];
    $trendPengeringanSuhu = [];
    $trendPengeringanKelembapan = [];
    $latestDates = [
      'bleaching' => null,
      'fermentasi' => null,
      'pengeringan' => null,
      'overall' => null
    ];

    // Data card ruangan
    foreach ($ruangan as $r) {
      $sensorSuhuList = collect($r->getDataSensor)->filter(fn($s) => str_starts_with($s->flag_sensor ?? '', 'suhu'));
      $sensorKelembapanList = collect($r->getDataSensor)->filter(fn($s) => str_starts_with($s->flag_sensor ?? '', 'kelembaban'));
      $sensorBlower = collect($r->getDataSensor)->first(fn($s) => str_starts_with($s->flag_sensor ?? '', 'blower'));

      $tipeRuangan = $r->tipe_ruangan;
      $isBleaching = ($tipeRuangan == 1);

      $avgSuhu = null;
      $avgKelembapan = null;
      $suhuBleaching = null;
      $latestDateForRoom = null;

      if (!$isBleaching) {
        $nilaiSuhu = [];
        $latestSensorDate = null;
        
        foreach ($sensorSuhuList as $sensor) {
          $recentData = NilaiSensorModel::where('id_sensor', $sensor->id_sensor)
            ->orderBy('created_at', 'desc')
            ->take(11)
            ->get(['nilai_sensor', 'created_at']);
          
          if ($recentData->isNotEmpty()) {
            $nilaiSuhu = array_merge($nilaiSuhu, $recentData->pluck('nilai_sensor')->toArray());
            
            $sensorLatestDate = $recentData->first()->created_at;
            if (!$latestSensorDate || $sensorLatestDate > $latestSensorDate) {
              $latestSensorDate = $sensorLatestDate;
            }
          }
        }
        $avgSuhu = count($nilaiSuhu) ? array_sum($nilaiSuhu) / count($nilaiSuhu) : null;

        $nilaiKelembapan = [];
        foreach ($sensorKelembapanList as $sensor) {
          $recentData = NilaiSensorModel::where('id_sensor', $sensor->id_sensor)
            ->orderBy('created_at', 'desc')
            ->take(11)
            ->pluck('nilai_sensor')
            ->toArray();
          $nilaiKelembapan = array_merge($nilaiKelembapan, $recentData);
        }
        $avgKelembapan = count($nilaiKelembapan) ? array_sum($nilaiKelembapan) / count($nilaiKelembapan) : null;
        
        $latestDateForRoom = $latestSensorDate;
      } else {
        $bleachingLatestDate = null;
        foreach ($sensorSuhuList as $sensor) {
          $suhuTerakhir = NilaiSensorModel::where('id_sensor', $sensor->id_sensor)
            ->whereTime('created_at', '>=', '07:00:00')
            ->whereTime('created_at', '<=', '10:00:00')
            ->latest()
            ->first();

          if ($suhuTerakhir) {
            $suhuBleaching = $suhuTerakhir->nilai_sensor;
            $bleachingLatestDate = $suhuTerakhir->created_at;
            break;
          }
        }
        $avgSuhu = $suhuBleaching;
        $latestDateForRoom = $bleachingLatestDate;
      }

      $nilaiBlower = $sensorBlower
        ? ModeBlowerModel::where('id_sensor', $sensorBlower->id_sensor)->latest()->first()
        : null;

      $statusInfo = $this->getStatusInfo($tipeRuangan, $avgSuhu, $avgKelembapan);

      $formattedDate = $latestDateForRoom 
        ? Carbon::parse($latestDateForRoom)->format('d M Y')
        : 'Belum ada data';

      $dataRuangan[$tipeRuangan] = [
        'id_ruangan' => $r->id_ruangan,
        'nama_ruangan' => $r->nama_ruangan,
        'tipe_ruangan' => $tipeRuangan,
        'suhu' => $avgSuhu ? number_format($avgSuhu, 2) : '-',
        'kelembapan' => $avgKelembapan ? number_format($avgKelembapan, 2) : '-',
        'suhu_bleaching' => $suhuBleaching ? number_format($suhuBleaching, 1) : null,
        'blower' => $nilaiBlower->nilai_sensor ?? '-',
        'status' => $statusInfo['status'],
        'status_color' => $statusInfo['color'],
        'status_icon' => $statusInfo['icon'],
        'is_bleaching' => $isBleaching,
        'latest_date' => $formattedDate,
        'latest_datetime' => $latestDateForRoom ? Carbon::parse($latestDateForRoom)->format('d M Y H:i') : null,
      ];

      if ($latestDateForRoom) {
        if ($tipeRuangan == 1) {
          if (!$latestDates['bleaching'] || $latestDateForRoom > $latestDates['bleaching']) {
            $latestDates['bleaching'] = $latestDateForRoom;
          }
        } elseif ($tipeRuangan == 2) {
          if (!$latestDates['fermentasi'] || $latestDateForRoom > $latestDates['fermentasi']) {
            $latestDates['fermentasi'] = $latestDateForRoom;
          }
        } elseif ($tipeRuangan == 3) {
          if (!$latestDates['pengeringan'] || $latestDateForRoom > $latestDates['pengeringan']) {
            $latestDates['pengeringan'] = $latestDateForRoom;
          }
        }
        
        if (!$latestDates['overall'] || $latestDateForRoom > $latestDates['overall']) {
          $latestDates['overall'] = $latestDateForRoom;
        }
      }

      // Data untuk grafik
      if (!$isBleaching) {
        // Grafik untuk fermentasi & pengeringan
        if ($sensorSuhuList->isNotEmpty()) {
          $firstSuhu = $sensorSuhuList->first();
          $grafikSuhu[$r->nama_ruangan] = collect(
            NilaiSensorModel::where('id_sensor', $firstSuhu->id_sensor)
              ->orderBy('created_at', 'desc')
              ->take(11)
              ->get(['nilai_sensor', 'created_at'])
          )
            ->reverse()
            ->values()
            ->map(fn($row) => [
              'nilai' => (float) $row->nilai_sensor,
              'waktu' => Carbon::parse($row->created_at)->format('H:i'),
            ]);
        }

        if ($sensorKelembapanList->isNotEmpty()) {
          $firstKelembapan = $sensorKelembapanList->first();
          $grafikKelembapan[$r->nama_ruangan] = collect(
            NilaiSensorModel::where('id_sensor', $firstKelembapan->id_sensor)
              ->orderBy('created_at', 'desc')
              ->take(11)
              ->get(['nilai_sensor', 'created_at'])
          )
            ->reverse()
            ->values()
            ->map(fn($row) => [
              'nilai' => (float) $row->nilai_sensor,
              'waktu' => Carbon::parse($row->created_at)->format('H:i'),
            ]);
        }
      } else {
        if ($sensorSuhuList->isNotEmpty()) {
          $firstSuhu = $sensorSuhuList->first();
          $latestDate = NilaiSensorModel::where('id_sensor', $firstSuhu->id_sensor)
            ->whereTime('created_at', '>=', '07:00:00')
            ->whereTime('created_at', '<=', '10:00:00')
            ->latest('created_at')
            ->value('created_at');

          if ($latestDate) {
            $grafikBleaching[$r->nama_ruangan] = collect(
              NilaiSensorModel::where('id_sensor', $firstSuhu->id_sensor)
                ->whereDate('created_at', Carbon::parse($latestDate)->format('Y-m-d'))
                ->whereTime('created_at', '>=', '07:00:00')
                ->whereTime('created_at', '<=', '10:00:00')
                ->orderBy('created_at', 'asc')
                ->get(['nilai_sensor', 'created_at'])
            )
              ->map(fn($row) => [
                'nilai' => (float) $row->nilai_sensor,
                'waktu' => Carbon::parse($row->created_at)->format('H:i'),
              ]);
          }
        }
      }
    }

    // grafik trend 14 hari
    $getDailyAverages = function ($sensorCollection, $timeStart = null, $timeEnd = null) {
      if ($sensorCollection->isEmpty()) {
        return [];
      }

      $sensorIds = $sensorCollection->pluck('id_sensor')->toArray();

      try {
        $query = NilaiSensorModel::whereIn('id_sensor', $sensorIds);

        if ($timeStart && $timeEnd) {
          $query->whereRaw("TIME(created_at) BETWEEN ? AND ?", [$timeStart, $timeEnd]);
        }

        $allData = $query->orderBy('created_at', 'desc')
          ->take(20000)
          ->get(['nilai_sensor', 'created_at']);

        if ($allData->isEmpty()) {
          return [];
        }

        $grouped = [];

        foreach ($allData as $item) {
          $date = Carbon::parse($item->created_at)->format('Y-m-d');
          $grouped[$date][] = (float) $item->nilai_sensor;
        }

        $dates = array_keys($grouped);
        rsort($dates);

        $selected = array_slice($dates, 0, 14);

        $results = [];
        foreach (array_reverse($selected) as $date) {
          $values = $grouped[$date];

          $results[] = [
            'tanggal' => Carbon::parse($date)->format('d M'),
            'tanggal_full' => $date,
            'nilai' => round(array_sum($values) / count($values), 2),
          ];
        }

        return $results;

      } catch (\Exception $e) {
        return [];
      }
    };


    foreach ($ruangan as $r) {
      $sensorSuhuList = collect($r->getDataSensor)->filter(fn($s) => str_starts_with($s->flag_sensor ?? '', 'suhu'));
      $sensorKelembapanList = collect($r->getDataSensor)->filter(fn($s) => str_starts_with($s->flag_sensor ?? '', 'kelembaban'));

      if ($r->tipe_ruangan == 1) {
        $trendBleaching = [
          'nama_ruangan' => $r->nama_ruangan,
          'data' => $getDailyAverages($sensorSuhuList, '07:00:00', '10:00:00'),
          'latest_date' => $latestDates['bleaching'] ? Carbon::parse($latestDates['bleaching'])->format('d M Y') : null
        ];
      } elseif ($r->tipe_ruangan == 2) {
        $trendFermentasiSuhu = [
          'nama_ruangan' => $r->nama_ruangan,
          'data' => $getDailyAverages($sensorSuhuList),
          'latest_date' => $latestDates['fermentasi'] ? Carbon::parse($latestDates['fermentasi'])->format('d M Y') : null
        ];

        $trendFermentasiKelembapan = [
          'nama_ruangan' => $r->nama_ruangan,
          'data' => $getDailyAverages($sensorKelembapanList),
          'latest_date' => $latestDates['fermentasi'] ? Carbon::parse($latestDates['fermentasi'])->format('d M Y') : null
        ];
      } elseif ($r->tipe_ruangan == 3) {
        $trendPengeringanSuhu = [
          'nama_ruangan' => $r->nama_ruangan,
          'data' => $getDailyAverages($sensorSuhuList),
          'latest_date' => $latestDates['pengeringan'] ? Carbon::parse($latestDates['pengeringan'])->format('d M Y') : null
        ];

        $trendPengeringanKelembapan = [
          'nama_ruangan' => $r->nama_ruangan,
          'data' => $getDailyAverages($sensorKelembapanList),
          'latest_date' => $latestDates['pengeringan'] ? Carbon::parse($latestDates['pengeringan'])->format('d M Y') : null
        ];
      }
    }

    $formattedLatestDates = [
      'bleaching' => $latestDates['bleaching'] ? Carbon::parse($latestDates['bleaching'])->format('d M Y') : 'Belum ada data',
      'fermentasi' => $latestDates['fermentasi'] ? Carbon::parse($latestDates['fermentasi'])->format('d M Y') : 'Belum ada data',
      'pengeringan' => $latestDates['pengeringan'] ? Carbon::parse($latestDates['pengeringan'])->format('d M Y') : 'Belum ada data',
      'overall' => $latestDates['overall'] ? Carbon::parse($latestDates['overall'])->format('d M Y') : 'Belum ada data',
      'overall_with_time' => $latestDates['overall'] ? Carbon::parse($latestDates['overall'])->format('d M Y H:i') : 'Belum ada data',
    ];

    // Return data to view
    return view('admin.dashboard.index', [
      'gudang' => $gudang,
      'dataRuangan' => $dataRuangan,
      'grafikSuhu' => $grafikSuhu,
      'grafikKelembapan' => $grafikKelembapan,
      'grafikBleaching' => $grafikBleaching,
      'trendBleaching' => $trendBleaching,
      'trendFermentasiSuhu' => $trendFermentasiSuhu,
      'trendFermentasiKelembapan' => $trendFermentasiKelembapan,
      'trendPengeringanSuhu' => $trendPengeringanSuhu,
      'trendPengeringanKelembapan' => $trendPengeringanKelembapan,
      'latestDates' => $formattedLatestDates,
    ]);
  }

  private function getStatusInfo($tipeRuangan, $suhu, $kelembapan)
  {
    if ($suhu === null) {
      return ['status' => 'Tidak Ada Data', 'color' => 'secondary', 'icon' => '❓'];
    }

    switch ($tipeRuangan) {
      case 1: // Bleaching
        if ($suhu >= 50 && $suhu <= 70) {
          return ['status' => 'Normal', 'color' => 'success', 'icon' => '✅'];
        } elseif ($suhu < 50) {
          return ['status' => 'Suhu Rendah', 'color' => 'warning', 'icon' => '⚠️'];
        } else {
          return ['status' => 'Suhu Tinggi', 'color' => 'danger', 'icon' => '🔥'];
        }

      case 2: // Fermentasi
      case 3: // Pengeringan
        $suhuNormal = ($suhu >= 20 && $suhu <= 30);
        $kelembapanNormal = ($kelembapan !== null && $kelembapan > 80);

        if ($suhuNormal && $kelembapanNormal) {
          return ['status' => 'Normal', 'color' => 'success', 'icon' => '✅'];
        } elseif (!$suhuNormal && $kelembapanNormal) {
          return ['status' => 'Suhu Tidak Normal', 'color' => 'warning', 'icon' => '🌡️'];
        } elseif ($suhuNormal && !$kelembapanNormal) {
          return ['status' => 'Kelembapan Rendah', 'color' => 'warning', 'icon' => '💧'];
        } else {
          return ['status' => 'Kritis', 'color' => 'danger', 'icon' => '🚨'];
        }

      default:
        return ['status' => 'Tidak Dikenal', 'color' => 'secondary', 'icon' => '❓'];
    }
  }
}