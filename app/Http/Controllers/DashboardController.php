<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GudangModel;
use App\Models\RuanganModel;
use App\Models\SensorModel;
use App\Models\NilaiSensorModel;
use App\Models\ModeBlowerModel;
use Carbon\Carbon;

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
        $perbandinganSuhu = [];
        $perbandinganKelembapan = [];
        $perbandinganBleaching = [];

        
        // data grafik
        foreach ($ruangan as $r) {
            $sensorSuhuList = collect($r->getDataSensor)->filter(fn($s) => str_starts_with($s->flag_sensor ?? '', 'suhu'));
            $sensorKelembapanList = collect($r->getDataSensor)->filter(fn($s) => str_starts_with($s->flag_sensor ?? '', 'kelembaban'));
            $sensorBlower = collect($r->getDataSensor)->first(fn($s) => str_starts_with($s->flag_sensor ?? '', 'blower'));

            $tipeRuangan = $r->tipe_ruangan;
            $isBleaching = ($tipeRuangan == 1);
            
            $avgSuhu = null;
            $avgKelembapan = null;
            $suhuBleaching = null;

            if (!$isBleaching) {
                $nilaiSuhu = [];
                foreach ($sensorSuhuList as $sensor) {
                    $recentData = NilaiSensorModel::where('id_sensor', $sensor->id_sensor)
                        ->orderBy('created_at', 'desc')
                        ->take(11)
                        ->pluck('nilai_sensor')
                        ->toArray();
                    $nilaiSuhu = array_merge($nilaiSuhu, $recentData);
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
            } else {
                foreach ($sensorSuhuList as $sensor) {
                    $suhuTerakhir = NilaiSensorModel::where('id_sensor', $sensor->id_sensor)
                        ->whereTime('created_at', '>=', '07:00:00')
                        ->whereTime('created_at', '<=', '10:00:00')
                        ->latest()
                        ->first();
                    
                    if ($suhuTerakhir) {
                        $suhuBleaching = $suhuTerakhir->nilai_sensor;
                        break;
                    }
                }
                $avgSuhu = $suhuBleaching;
            }

            $nilaiBlower = $sensorBlower
                ? ModeBlowerModel::where('id_sensor', $sensorBlower->id_sensor)->latest()->first()
                : null;

            $statusInfo = $this->getStatusInfo($tipeRuangan, $avgSuhu, $avgKelembapan);

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
            ];

            if (!$isBleaching) {
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


        //data perbandingan
        $latestDate = NilaiSensorModel::selectRaw('DATE(created_at) as dt')
            ->orderByDesc('dt')
            ->value('dt');

        $prevDate = null;
        if ($latestDate) {
            $prevDate = NilaiSensorModel::selectRaw('DATE(created_at) as dt')
                ->whereRaw('DATE(created_at) < ?', [$latestDate])
                ->orderByDesc('dt')
                ->value('dt');
        }

        $avgForSensorsOnDate = function ($sensorCollection, $date, $timeStart = null, $timeEnd = null) {
            $values = [];
            foreach ($sensorCollection as $sensor) {
                $query = NilaiSensorModel::where('id_sensor', $sensor->id_sensor)
                    ->whereDate('created_at', $date);

                if ($timeStart && $timeEnd) {
                    $query->whereTime('created_at', '>=', $timeStart)
                          ->whereTime('created_at', '<=', $timeEnd);
                }

                $rows = $query->pluck('nilai_sensor')->toArray();
                if (!empty($rows)) {
                    $values = array_merge($values, $rows);
                }
            }

            if (count($values) === 0) return null;
            return array_sum($values) / count($values);
        };


        foreach ($ruangan as $r) {
            $nama = $r->nama_ruangan;
            $sensorSuhuList = collect($r->getDataSensor)->filter(fn($s) => str_starts_with($s->flag_sensor ?? '', 'suhu'));
            $sensorKelembapanList = collect($r->getDataSensor)->filter(fn($s) => str_starts_with($s->flag_sensor ?? '', 'kelembaban'));

            $hariIniSuhu = $latestDate ? $avgForSensorsOnDate($sensorSuhuList, $latestDate) : null;
            $kemarinSuhu = $prevDate ? $avgForSensorsOnDate($sensorSuhuList, $prevDate) : null;

            $hariIniKelembapan = $latestDate ? $avgForSensorsOnDate($sensorKelembapanList, $latestDate) : null;
            $kemarinKelembapan = $prevDate ? $avgForSensorsOnDate($sensorKelembapanList, $prevDate) : null;

            if ($r->tipe_ruangan == 1) {
                $hariIniBleach = $latestDate ? $avgForSensorsOnDate($sensorSuhuList, $latestDate, '07:00:00', '10:00:00') : null;
                $kemarinBleach = $prevDate ? $avgForSensorsOnDate($sensorSuhuList, $prevDate, '07:00:00', '10:00:00') : null;

                $perbandinganBleaching[$nama] = [
                    'hari_ini' => $hariIniBleach !== null ? round($hariIniBleach, 2) : null,
                    'kemarin'  => $kemarinBleach !== null ? round($kemarinBleach, 2) : null,
                ];
            }

            $perbandinganSuhu[$nama] = [
                'hari_ini' => $hariIniSuhu !== null ? round($hariIniSuhu, 2) : null,
                'kemarin'  => $kemarinSuhu !== null ? round($kemarinSuhu, 2) : null,
            ];

            $perbandinganKelembapan[$nama] = [
                'hari_ini' => $hariIniKelembapan !== null ? round($hariIniKelembapan, 2) : null,
                'kemarin'  => $kemarinKelembapan !== null ? round($kemarinKelembapan, 2) : null,
            ];
        }

        
        // return data to view
        return view('admin.dashboard.index', [
            'gudang' => $gudang,
            'dataRuangan' => $dataRuangan,
            'grafikSuhu' => $grafikSuhu,
            'grafikKelembapan' => $grafikKelembapan,
            'grafikBleaching' => $grafikBleaching,
            'perbandinganSuhu' => $perbandinganSuhu,
            'perbandinganKelembapan' => $perbandinganKelembapan,
            'perbandinganBleaching' => $perbandinganBleaching,
            'latestDate' => $latestDate,
            'prevDate' => $prevDate
        ]);
    }

    //data status
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