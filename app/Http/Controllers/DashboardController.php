<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GudangModel;
use App\Models\RuanganModel;
use App\Models\SensorModel;
use App\Models\NilaiSensorModel;
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
            $sensorSuhu = $r->getDataSensor->where('flag_sensor', 'suhu')->first();
            $sensorKelembapan = $r->getDataSensor->where('flag_sensor', 'kelembaban')->first();

            $nilaiSuhuAkhir = $sensorSuhu ? NilaiSensorModel::where('id_sensor', $sensorSuhu->id_sensor)->latest()->first() : null;
            $nilaiKelembapanAkhir = $sensorKelembapan ? NilaiSensorModel::where('id_sensor', $sensorKelembapan->id_sensor)->latest()->first() : null;

            $status = 'Perlu Cek';
            if ($nilaiSuhuAkhir && $nilaiKelembapanAkhir) {
                $suhu = $nilaiSuhuAkhir->nilai_sensor;
                $kelembapan = $nilaiKelembapanAkhir->nilai_sensor;

                if ($suhu >= 25 && $suhu <= 35 && $kelembapan >= 50 && $kelembapan <= 80) {
                    $status = 'Normal';
                }

            }

            $dataRuangan[] = [
                'id_ruangan' => $r->id_ruangan,
                'nama_ruangan' => $r->nama_ruangan,
                'suhu' => $nilaiSuhuAkhir->nilai_sensor ?? '_',
                'kelembapan' => $nilaiKelembapanAkhir->nilai_sensor ?? '_',
                'status' => $status,
            ];


            // nilai grafik suhu
            if ($sensorSuhu) {
                $grafikSuhu[$r->nama_ruangan] = NilaiSensorModel::where('id_sensor', $sensorSuhu->id_sensor)
                    ->orderBy('created_at', 'asc')
                    ->take(5)
                    ->get(['nilai_sensor', 'created_at'])
                    ->map(function ($row) {
                        return [
                            'nilai' => (float) $row->nilai_sensor,
                            'waktu' => Carbon::parse($row->created_at)->format('H:i'),
                        ];
                    });
            }

            //nilai grafik kelembapan
            if ($sensorKelembapan) {
                $grafikKelembapan[$r->nama_ruangan] = NilaiSensorModel::where('id_sensor', $sensorKelembapan->id_sensor)
                    ->orderBy('created_at', 'asc')
                    ->take(5)
                    ->get(['nilai_sensor', 'created_at'])
                    ->map(function ($row) {
                        return [
                            'nilai' => (float) $row->nilai_sensor,
                            'waktu' => Carbon::parse($row->created_at)->format('H:i'),
                        ];
                    });
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
