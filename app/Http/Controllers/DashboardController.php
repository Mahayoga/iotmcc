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

        return view('admin.dashboard.index', [
            'gudang' => $gudang,
            'dataRuangan' => $dataRuangan,
            'grafikSuhu' => $grafikSuhu,
            'grafikKelembapan' => $grafikKelembapan,
            'grafikBleaching' => $grafikBleaching
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