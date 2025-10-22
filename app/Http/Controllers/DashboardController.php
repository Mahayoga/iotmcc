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
        //dd($gudang, $ruangan);

        $dataRuangan = [];
        $grafikSuhu = [];
        $grafikKelembapan = [];

        // nilai card
        foreach ($ruangan as $r) {
            $sensorSuhuList = $r->getDataSensor->filter(fn($s) => str_starts_with($s->flag_sensor, 'suhu'));
            $sensorKelembapanList = $r->getDataSensor->filter(fn($s) => str_starts_with($s->flag_sensor, 'kelembaban'));
            $sensorBlower = $r->getDataSensor->first(fn($s) => str_starts_with($s->flag_sensor, 'blower'));

            $nilaiSuhu = [];
            foreach ($sensorSuhuList as $sensor) {
                $latest = NilaiSensorModel::where('id_sensor', $sensor->id_sensor)->latest()->first();
                if ($latest)
                    $nilaiSuhu[] = $latest->nilai_sensor;
            }
            $avgSuhu = count($nilaiSuhu) ? array_sum($nilaiSuhu) / count($nilaiSuhu) : null;

            $nilaiKelembapan = [];
            foreach ($sensorKelembapanList as $sensor) {
                $latest = NilaiSensorModel::where('id_sensor', $sensor->id_sensor)->latest()->first();
                if ($latest)
                    $nilaiKelembapan[] = $latest->nilai_sensor;
            }
            $avgKelembapan = count($nilaiKelembapan) ? array_sum($nilaiKelembapan) / count($nilaiKelembapan) : null;

            $nilaiBlower = $sensorBlower
                ? ModeBlowerModel::where('id_sensor', $sensorBlower->id_sensor)->latest()->first()
                : null;

            $status = 'Perlu Cek';
            if ($avgSuhu !== null && $avgKelembapan !== null) {
                if ($avgSuhu >= 25 && $avgSuhu <= 35 && $avgKelembapan >= 30 && $avgKelembapan <= 70) {
                    $status = 'Normal';
                }
            }

            $dataRuangan[] = [
                'id_ruangan' => $r->id_ruangan,
                'nama_ruangan' => $r->nama_ruangan,
                'suhu' => $avgSuhu ? number_format($avgSuhu, 2) : '_',
                'kelembapan' => $avgKelembapan ? number_format($avgKelembapan, 2) : '_',
                'blower' => $nilaiBlower->nilai_sensor ?? '_',
                'status' => $status,
            ];

            // untuk grafik
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
                        'waktu' => \Carbon\Carbon::parse($row->created_at)->format('H:i'),
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
                        'waktu' => \Carbon\Carbon::parse($row->created_at)->format('H:i'),
                    ]);
            }
        }


        // dd($dataRuangan, $grafikSuhu, $grafikKelembapan);

        return view('admin.dashboard.index', [
            'gudang' => $gudang,
            'dataRuangan' => $dataRuangan,
            'grafikSuhu' => $grafikSuhu,
            'grafikKelembapan' => $grafikKelembapan
        ]);
    }
}
