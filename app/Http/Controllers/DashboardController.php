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
            $sensorSuhuList = collect($r->getDataSensor)->filter(fn($s) => str_starts_with($s->flag_sensor, 'suhu'));
            $sensorKelembapanList = collect($r->getDataSensor)->filter(fn($s) => str_starts_with($s->flag_sensor, 'kelembaban'));
            $sensorBlower = collect($r->getDataSensor)->first(fn($s) => str_starts_with($s->flag_sensor, 'blower'));

            $isBleaching = str_contains(strtolower($r->nama_ruangan), 'bleaching');
            
            $avgSuhu = null;
            $avgKelembapan = null;
            $suhuBleaching = null;

            if (!$isBleaching) {
                $nilaiSuhu = [];
                foreach ($sensorSuhuList as $sensor) {
                    $latest = NilaiSensorModel::where('id_sensor', $sensor->id_sensor)->latest()->first();
                    if ($latest) $nilaiSuhu[] = $latest->nilai_sensor;
                }
                $avgSuhu = count($nilaiSuhu) ? array_sum($nilaiSuhu) / count($nilaiSuhu) : null;

                $nilaiKelembapan = [];
                foreach ($sensorKelembapanList as $sensor) {
                    $latest = NilaiSensorModel::where('id_sensor', $sensor->id_sensor)->latest()->first();
                    if ($latest) $nilaiKelembapan[] = $latest->nilai_sensor;
                }
                $avgKelembapan = count($nilaiKelembapan) ? array_sum($nilaiKelembapan) / count($nilaiKelembapan) : null;
            } else {
                // Untuk bleaching
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

            $status = 'Perlu Cek';
            if ($avgSuhu !== null) {
                if ($isBleaching) {
                    if ($avgSuhu >= 50 && $avgSuhu <= 70) {
                        $status = 'Normal';
                    }
                } else if ($avgKelembapan !== null) {
                    if ($avgSuhu >= 25 && $avgSuhu <= 35 && $avgKelembapan >= 30 && $avgKelembapan <= 70) {
                        $status = 'Normal';
                    }
                }
            }

            $dataRuangan[] = [
                'id_ruangan' => $r->id_ruangan,
                'nama_ruangan' => $r->nama_ruangan,
                'suhu' => $avgSuhu ? number_format($avgSuhu, 1) : '-',
                'kelembapan' => $avgKelembapan ? number_format($avgKelembapan, 1) : '-',
                'suhu_bleaching' => $suhuBleaching ? number_format($suhuBleaching, 1) : null, // Data khusus bleaching
                'blower' => $nilaiBlower->nilai_sensor ?? '-',
                'status' => $status,
                'is_bleaching' => $isBleaching,
            ];

            // Grafik data
            if (!$isBleaching) {
                // Grafik untuk ruangan
                if ($sensorSuhuList->isNotEmpty()) {
                    $firstSuhu = $sensorSuhuList->first();
                    $grafikSuhu[$r->nama_ruangan] = collect(
                        NilaiSensorModel::where('id_sensor', $firstSuhu->id_sensor)
                            ->orderBy('created_at', 'desc')
                            ->take(5)
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
                            ->take(5)
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
                // Grafik bleaching untuk jam 7 - 10 tgl terbaru
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
}