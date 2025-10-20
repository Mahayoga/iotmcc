<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GudangModel;
use App\Models\NilaiSensorModel;
use App\Models\SensorModel;


class RuanganPerebusanController extends Controller
{
     /**
     * Mengambil data suhu, kelembaban, dan status untuk ruang perebusan.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDataSuhu(string $id)
    {
        $dataSuhu = [];
        $dataWaktuSuhu = [];
        $dataKelembaban = [];
        $dataWaktuKelembaban = [];
        $statusRuangan = 0; // Default ke non-aktif
        $timerValue = null;

        try {
            $dataGudang = GudangModel::findOrFail($id);
            $dataRuangan = $dataGudang->getDataRuangan;

            foreach ($dataRuangan as $value) {
                if ($value->tipe_ruangan == 1) { // Ruang Perebusan
                    $statusRuangan = $value->status_ruangan;

                    foreach ($value->getDataSensor as $value2) {
                        if ($value2->flag_sensor == 'suhu') {
                             // Mengurutkan berdasarkan waktu ascending untuk grafik
                            foreach ($value2->getDataNilaiSensor()->orderBy('created_at', 'asc')->limit(30)->get() as $value3) {
                                $dataSuhu[] = $value3->nilai_sensor;
                                $dataWaktuSuhu[] = $value3->created_at->format('H:i:s');
                            }
                        } elseif ($value2->flag_sensor == 'kelembaban') {
                            foreach ($value2->getDataNilaiSensor()->orderBy('created_at', 'asc')->limit(30)->get() as $value3) {
                                $dataKelembaban[] = $value3->nilai_sensor;
                                $dataWaktuKelembaban[] = $value3->created_at->format('H:i:s');
                            }
                        } elseif ($value2->flag_sensor == 'timer') {
                            $latest = $value2->getDataNilaiSensor()->orderBy('created_at', 'desc')->first();
                            if ($latest) $timerValue = $latest->nilai_sensor;
                        }
                    }
                    // Hanya proses satu ruang perebusan per gudang
                    break; 
                }
            }

            return response()->json([
                'status' => true,
                'dataSuhu' => $dataSuhu,
                'dataKelembaban' => $dataKelembaban,
                'dataWaktuSuhu' => $dataWaktuSuhu,
                'dataWaktuKelembaban' => $dataWaktuKelembaban,
                'dataAvgSuhu' => count($dataSuhu) ? number_format(array_sum($dataSuhu) / count($dataSuhu), 1) : 0,
                'dataAvgKelembaban' => count($dataKelembaban) ? number_format(array_sum($dataKelembaban) / count($dataKelembaban), 1) : 0,
                'dataTimer' => $timerValue,
                'statusRuangan' => $statusRuangan,
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gudang dengan ID ' . $id . ' tidak ditemukan.',
            ], 404);
        } catch (\Exception $e) {
            // Log error untuk debugging di sisi server
            \Log::error("Error di RuanganPerebusanController: " . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan internal pada server.',
            ], 500);
        }
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
