<?php

namespace App\Services;

use App\Models\GudangModel;
use App\Models\ModeBlowerModel;
use App\Models\ModeTimerModel;
use App\Models\NilaiTimerModel;
use App\Models\SensorModel;
use Carbon\Carbon;

class SensorDataService
{
    public $LIMIT = 50;

    /**
     * Get sensor data for Blanching/Bleaching Room
     * Logic adapted from AlatBleachingController
     */

    public function getBlanchingData(string $gudangId)
    {
        $dataGudang = GudangModel::findOrFail($gudangId);
        $dataRuangan = $dataGudang->getDataRuangan;
        $dataSensor = [];
        $dataWaktuSensor = [];
        $nilaiSensorTemp = [];
        $waktuSensorTemp = [];
        $stddevTemp = [];

        $statusRuangan = null;
        $currentSuhuAccumulator = 0;
        $countSuhuSensors = 0;

        $selectRawQuery = '
            DATE(created_at) as tgl,
            HOUR(created_at) as jam,
            FLOOR(MINUTE(created_at)/15) as menit_group,
            AVG(nilai_sensor) as avg_nilai,
            MIN(created_at) as waktu_asli,
            STDDEV_SAMP(nilai_sensor) as stddev, -- Tambahkan stddev agar konsisten
            MIN(nilai_sensor) as min_nilai,
            MAX(nilai_sensor) as max_nilai
        ';

        $dateNow = '%' . date('Y-m-d') . '%';

        foreach ($dataRuangan as $value) {
            if ($value->tipe_ruangan == 1) {
                $statusRuangan = $value->status_ruangan;

                foreach ($value->getDataSensor as $value2) {
                    if (str_contains($value2->flag_sensor, 'timer')) {
                        continue;
                    }

                    $groupedData = $value2->getDataNilaiSensor()
                        ->selectRaw($selectRawQuery)
                        ->where('created_at', 'LIKE', $dateNow)
                        ->groupBy('tgl', 'jam', 'menit_group')
                        ->orderBy('waktu_asli', 'DESC')
                        ->limit($this->LIMIT)
                        ->get();

                    foreach ($groupedData as $value3) {
                        $nilaiSensorTemp[] = number_format($value3->avg_nilai, 2);
                        $waktuSensorTemp[] = date('G:i', Carbon::parse($value3->waktu_asli)->timestamp);
                        $stddevTemp[] = [Carbon::parse($value3->waktu_asli)->valueOf(), number_format($value3->stddev, 2)];
                    }

                    $latestData = $value2->getDataNilaiSensor()
                        ->where('created_at', 'LIKE', $dateNow)
                        ->orderBy('created_at', 'DESC')
                        ->first();

                    if ($latestData) {
                        if (str_contains($value2->flag_sensor, 'suhu')) {
                            $currentSuhuAccumulator += $latestData->nilai_sensor;
                            $countSuhuSensors++;
                        }
                    }

                    $avgCalculation = count($nilaiSensorTemp) > 0
                        ? number_format(array_sum($nilaiSensorTemp) / count($nilaiSensorTemp), 1)
                        : 0;

                    array_push($dataSensor, [
                        'type' => 'sensor',
                        'flag_sensor' => $value2->flag_sensor,
                        'value' => $nilaiSensorTemp,
                        'avg' => $avgCalculation,
                        'stddev' => $stddevTemp,
                    ]);

                    array_push($dataWaktuSensor, [
                        'type' => 'waktu',
                        'flag_sensor' => $value2->flag_sensor,
                        'value' => $waktuSensorTemp,
                    ]);

                    $nilaiSensorTemp = [];
                    $waktuSensorTemp = [];
                    $stddevTemp = [];
                }
                break;
            }
        }

        $rataRataSuhuFinal = $countSuhuSensors > 0
            ? number_format($currentSuhuAccumulator / $countSuhuSensors, 2)
            : 0;

        return [
            'statusRuangan' => $statusRuangan,
            'dataSensor' => $dataSensor,
            'dataWaktuSensor' => $dataWaktuSensor,
            'rataRataSuhu' => $rataRataSuhuFinal,
        ];
    }

    // public function getBlanchingData(string $gudangId)
    // {
    //     $dataGudang = GudangModel::findOrFail($gudangId);
    //     $dataRuangan = $dataGudang->getDataRuangan;

    //     $dataSensor = [];
    //     $dataWaktuSensor = [];
    //     $nilaiSensorTemp = [];
    //     $waktuSensorTemp = [];
    //     $statusRuangan = null;
    //     $suhuTotal = 0;
    //     $totalDataSuhu = 0;

    //     $selectRawQuery = '
    //     DATE(created_at) as tgl,
    //     HOUR(created_at) as jam,
    //     FLOOR(MINUTE(created_at)/15) as menit_group,
    //     AVG(nilai_sensor) as avg_nilai,
    //     MIN(created_at) as waktu_asli,
    //     MIN(nilai_sensor) as min_nilai,
    //     MAX(nilai_sensor) as max_nilai
    // ';

    //     foreach ($dataRuangan as $value) {
    //         if ($value->tipe_ruangan == 1) {
    //             $statusRuangan = $value->status_ruangan;

    //             foreach ($value->getDataSensor as $value2) {
    //                 if (str_contains($value2->flag_sensor, 'timer')) {
    //                     continue;
    //                 }

    //                 $dateNow = '%' . date('Y-m-d') . '%';
    //                 $usedDate = date('Y-m-d');

    //                 if ($value2->getDataNilaiSensor()->where('created_at', 'LIKE', $dateNow)->get()->isEmpty()) {
    //                     $temp = $value2->getDataNilaiSensor()->orderBy('created_at', 'DESC')->first();
    //                     if ($temp) {
    //                         $usedDate = Carbon::parse($temp->created_at)->format('Y-m-d');
    //                         $dateNow = '%' . $usedDate . '%';
    //                     }
    //                 }

    //                 $groupedData = $value2->getDataNilaiSensor()
    //                     ->selectRaw($selectRawQuery)
    //                     ->where('created_at', 'LIKE', $dateNow)
    //                     ->groupBy('tgl', 'jam', 'menit_group')
    //                     ->orderBy('waktu_asli', 'DESC')
    //                     ->limit($this->LIMIT)
    //                     ->get();

    //                 foreach ($groupedData as $value3) {
    //                     $avgNilai = number_format($value3->avg_nilai, 2);
    //                     $nilaiSensorTemp[] = $avgNilai;
    //                     $waktuSensorTemp[] = date('G:i', Carbon::parse($value3->waktu_asli)->timestamp);

    //                     if (str_contains($value2->flag_sensor, 'suhu')) {
    //                         $suhuTotal += floatval($avgNilai);
    //                         $totalDataSuhu++;
    //                     }
    //                 }

    //                 array_push($dataSensor, [
    //                     'type' => 'sensor',
    //                     'flag_sensor' => $value2->flag_sensor,
    //                     'value' => $nilaiSensorTemp,
    //                     'avg' => count($nilaiSensorTemp) > 0 ? number_format(array_sum($nilaiSensorTemp) / count($nilaiSensorTemp), 1) : 0,
    //                 ]);

    //                 array_push($dataWaktuSensor, [
    //                     'type' => 'waktu',
    //                     'flag_sensor' => $value2->flag_sensor,
    //                     'value' => $waktuSensorTemp,
    //                 ]);

    //                 $nilaiSensorTemp = [];
    //                 $waktuSensorTemp = [];
    //             }
    //             break;
    //         }
    //     }

    //     $rataRataSuhu = $totalDataSuhu > 0 ? number_format($suhuTotal / $totalDataSuhu, 2) : 0;

    //     return [
    //         'statusRuangan' => $statusRuangan,
    //         'dataSensor' => $dataSensor,
    //         'dataWaktuSensor' => $dataWaktuSensor,
    //         'rataRataSuhu' => $rataRataSuhu,
    //     ];
    // }

    /**
     * Start or Stop the Timer
     */
    public function toggleTimer(string $gudangId)
    {
        $dataGudang = GudangModel::findOrFail($gudangId);
        $dataRuangan = $dataGudang->getDataRuangan;
        $dataTimer = [];

        foreach ($dataRuangan as $ruangan) {
            if ($ruangan->tipe_ruangan == 1) {
                foreach ($ruangan->getDataSensor as $sensor) {
                    if (in_array($sensor->flag_sensor, ['timer_1', 'timer_2'])) {

                        $nilaiTimer = NilaiTimerModel::where('id_sensor', $sensor->id_sensor)
                            ->orderBy('created_at', 'desc')->first();

                        $statusSekarang = $nilaiTimer ? $nilaiTimer->flag_timer : 'stop';

                        if ($statusSekarang == 'start') {

                            NilaiTimerModel::create([
                                'flag_timer' => 'stop',
                                'nilai_timer' => microtime(true),
                                'id_sensor' => $sensor->id_sensor,
                                'rssi' => 0,
                                'snr' => 0,
                            ]);

                            $dataTimer = [
                                'status' => true,
                                'status_timer' => 'stop',
                                'sisa_timer' => 0,
                            ];
                        } else {
                            NilaiTimerModel::create([
                                'flag_timer' => 'start',
                                'nilai_timer' => microtime(true),
                                'id_sensor' => $sensor->id_sensor,
                                'rssi' => 0,
                                'snr' => 0,
                            ]);

                            $modeTimer = ModeTimerModel::where('id_sensor', $sensor->id_sensor)->first();
                            $limit = $modeTimer ? $modeTimer->limit_timer : 0;

                            $dataTimer = [
                                'status' => true,
                                'status_timer' => 'start',
                                'sisa_timer' => number_format($limit, 2),
                            ];
                        }
                    }
                }
            }
        }

        return $dataTimer;
    }

    /**
     * Get Timer Data and Check Limits
     */
    public function getTimerData(string $gudangId)
    {
        $dataGudang = GudangModel::findOrFail($gudangId);
        $dataRuangan = $dataGudang->getDataRuangan;
        $dataTimer = [];

        foreach ($dataRuangan as $ruangan) {
            if ($ruangan->tipe_ruangan == 1) {
                foreach ($ruangan->getDataSensor as $sensor) {
                    if (in_array($sensor->flag_sensor, ['timer_1', 'timer_2'])) {

                        $nilaiTimer = NilaiTimerModel::where('id_sensor', $sensor->id_sensor)
                            ->orderBy('created_at', 'desc')->first();

                        $modeTimer = ModeTimerModel::where('id_sensor', $sensor->id_sensor)->first();
                        $limit = $modeTimer ? (float) $modeTimer->limit_timer : 0;

                        $flagTimer = $nilaiTimer ? $nilaiTimer->flag_timer : 'stop';
                        $startTime = $nilaiTimer ? (float) $nilaiTimer->nilai_timer : 0;
                        $sisaTimer = 0;

                        if ($flagTimer == 'stop') {
                            $dataTimer[] = [
                                'flag_sensor' => $sensor->flag_sensor,
                                'flag_timer' => 'stop',
                                'nilai_timer' => $startTime,
                                'limit_timer' => $limit,
                                'sisa_timer' => 0,
                                'updated_at' => $nilaiTimer ? $nilaiTimer->created_at->format('Y-m-d H:i:s') : null,
                            ];

                            continue;
                        }


                        $elapsedTime = microtime(true) - $startTime;

                        $sisaTimer = $limit - $elapsedTime;

                        if ($sisaTimer <= 0) {
                            $sisaTimer = 0;

                            NilaiTimerModel::create([
                                'flag_timer' => 'stop',
                                'nilai_timer' => $startTime + $limit,
                                'id_sensor' => $sensor->id_sensor,
                                'rssi' => 0,
                                'snr' => 0,
                            ]);

                            // Update status lokal untuk return data
                            $flagTimer = 'stop';
                        }

                        $dataTimer[] = [
                            'flag_sensor' => $sensor->flag_sensor,
                            'flag_timer' => $flagTimer,
                            'nilai_timer' => $startTime,
                            'limit_timer' => $limit,
                            'sisa_timer' => number_format($sisaTimer, 2, '.', ''), // Format float
                            'updated_at' => $nilaiTimer ? $nilaiTimer->created_at->format('Y-m-d H:i:s') : null,
                        ];
                    }
                }
            }
        }

        return $dataTimer;
    }

    /**
     * Set Timer Limit
     */
    public function setTimerLimit(string $gudangId, int $limit, string $flagSensor)
    {
        $dataGudang = GudangModel::findOrFail($gudangId);
        $dataRuangan = $dataGudang->getDataRuangan;
        $found = false;

        foreach ($dataRuangan as $ruangan) {
            if ($ruangan->tipe_ruangan == 1) {
                foreach ($ruangan->getDataSensor as $sensor) {
                    if ($sensor->flag_sensor === $flagSensor) {
                        ModeTimerModel::updateOrCreate(
                            ['id_sensor' => $sensor->id_sensor],
                            ['limit_timer' => $limit * 60]
                        );

                        $found = true;
                    }
                }
            }
        }

        return $found;
    }

    // /**
    //  * Get sensor data for Fermentation Room with averaging logic
    //  */
    // public function getFermentasiData(string $gudangId)
    // {
    //     $dataSensor = [];
    //     $dataWaktuSensor = [];
    //     $dataGudang = GudangModel::findOrFail($gudangId);
    //     $dataRuangan = $dataGudang->getDataRuangan;
    //     $nilaiSensorTemp = [];
    //     $waktuSensorTemp = [];
    //     $stddevTemp = [];
    //     $statusRuangan = [];
    //     $currentSuhu = 0;
    //     $currentKelembaban = 0;
    //     $suhuSensors = 0;
    //     $kelembabanSensors = 0;

    //     $selectRawQuery = '
    //     DATE(created_at) as tgl,
    //     HOUR(created_at) as jam,
    //     FLOOR(MINUTE(created_at)/15) as menit_group,
    //     AVG(nilai_sensor) as avg_nilai,
    //     MIN(created_at) as waktu_asli,
    //     STDDEV_SAMP(nilai_sensor) as stddev,
    //     MIN(nilai_sensor) as min_nilai,
    //     MAX(nilai_sensor) as max_nilai
    // ';

    //     foreach ($dataRuangan as $value) {
    //         if ($value->tipe_ruangan == 2) {
    //             $statusRuangan = $value->status_ruangan;
    //             foreach ($value->getDataSensor as $value2) {
    //                 $dateNow = '%'.date('Y-m-d').'%';
    //                 $usedDate = date('Y-m-d');

    //                 if ($value2->getDataNilaiSensor()->where('created_at', 'LIKE', $dateNow)->get()->isEmpty()) {
    //                     $temp = $value2->getDataNilaiSensor()->orderBy('created_at', 'DESC')->first();
    //                     if ($temp) {
    //                         $usedDate = Carbon::parse($temp->created_at)->format('Y-m-d');
    //                         $dateNow = '%'.$usedDate.'%';
    //                     }
    //                 }

    //                 $groupedData = $value2->getDataNilaiSensor()
    //                     ->selectRaw($selectRawQuery)
    //                     ->where('created_at', 'LIKE', $dateNow)
    //                     ->groupBy('tgl', 'jam', 'menit_group')
    //                     ->orderBy('waktu_asli', 'DESC')
    //                     ->limit($this->LIMIT)
    //                     ->get();

    //                 foreach ($groupedData as $value3) {
    //                     $nilaiSensorTemp[] = number_format($value3->avg_nilai, 2);
    //                     $waktuSensorTemp[] = date('G:i', Carbon::parse($value3->waktu_asli)->timestamp);
    //                     $stddevTemp[] = [Carbon::parse($value3->waktu_asli)->valueOf(), number_format($value3->stddev, 2)];
    //                 }

    //                 $latestData = $value2->getDataNilaiSensor()
    //                     ->where('created_at', 'LIKE', $dateNow)
    //                     ->orderBy('created_at', 'DESC')
    //                     ->first();

    //                 if ($latestData) {
    //                     if (str_contains($value2->flag_sensor, 'suhu')) {
    //                         $currentSuhu += (int) $latestData->nilai_sensor;
    //                         $suhuSensors++;
    //                     } elseif (str_contains($value2->flag_sensor, 'kelembaban')) {
    //                         $currentKelembaban += (int) $latestData->nilai_sensor;
    //                         $kelembabanSensors++;
    //                     }
    //                 }

    //                 array_push($dataSensor, [
    //                     'type' => 'sensor',
    //                     'flag_sensor' => $value2->flag_sensor,
    //                     'value' => $nilaiSensorTemp,
    //                     'avg' => count($nilaiSensorTemp) > 0 ? number_format(array_sum($nilaiSensorTemp) / count($nilaiSensorTemp), 1) : 0,
    //                     'stddev' => $stddevTemp,
    //                 ]);

    //                 array_push($dataWaktuSensor, [
    //                     'type' => 'waktu',
    //                     'flag_sensor' => $value2->flag_sensor,
    //                     'value' => $waktuSensorTemp,
    //                 ]);

    //                 $nilaiSensorTemp = [];
    //                 $waktuSensorTemp = [];
    //                 $stddevTemp = [];
    //             }
    //         }
    //     }

    //     return [
    //         'statusRuangan' => $statusRuangan,
    //         'dataSensor' => $dataSensor,
    //         'dataWaktuSensor' => $dataWaktuSensor,
    //         'currentSuhu' => $suhuSensors > 0 ? number_format($currentSuhu / $suhuSensors, 2) : 0,
    //         'currentKelembaban' => $kelembabanSensors > 0 ? number_format($currentKelembaban / $kelembabanSensors, 2) : 0,
    //     ];
    // }

    /**
     * Get sensor data for Fermentation Room (STRICT TODAY ONLY)
     */
    public function getFermentasiData(string $gudangId)
    {
        $dataSensor = [];
        $dataWaktuSensor = [];
        $dataGudang = GudangModel::findOrFail($gudangId);
        $dataRuangan = $dataGudang->getDataRuangan;
        $nilaiSensorTemp = [];
        $waktuSensorTemp = [];
        $stddevTemp = [];
        $statusRuangan = [];

        $currentSuhu = 0;
        $currentKelembaban = 0;
        $suhuSensors = 0;
        $kelembabanSensors = 0;

        $selectRawQuery = '
            DATE(created_at) as tgl,
            HOUR(created_at) as jam,
            FLOOR(MINUTE(created_at)/15) as menit_group,
            AVG(nilai_sensor) as avg_nilai,
            MIN(created_at) as waktu_asli,
            STDDEV_SAMP(nilai_sensor) as stddev,
            MIN(nilai_sensor) as min_nilai,
            MAX(nilai_sensor) as max_nilai
        ';

        $dateNow = '%' . date('Y-m-d') . '%';

        foreach ($dataRuangan as $value) {
            if ($value->tipe_ruangan == 2) {
                $statusRuangan = $value->status_ruangan;

                foreach ($value->getDataSensor as $value2) {


                    $groupedData = $value2->getDataNilaiSensor()
                        ->selectRaw($selectRawQuery)
                        ->where('created_at', 'LIKE', $dateNow)
                        ->groupBy('tgl', 'jam', 'menit_group')
                        ->orderBy('waktu_asli', 'DESC')
                        ->limit($this->LIMIT)
                        ->get();

                    foreach ($groupedData as $value3) {
                        $nilaiSensorTemp[] = number_format($value3->avg_nilai, 2);
                        $waktuSensorTemp[] = date('G:i', Carbon::parse($value3->waktu_asli)->timestamp);
                        $stddevTemp[] = [Carbon::parse($value3->waktu_asli)->valueOf(), number_format($value3->stddev, 2)];
                    }

                    $latestData = $value2->getDataNilaiSensor()
                        ->where('created_at', 'LIKE', $dateNow)
                        ->orderBy('created_at', 'DESC')
                        ->first();

                    if ($latestData) {
                        if (str_contains($value2->flag_sensor, 'suhu')) {
                            $currentSuhu += $latestData->nilai_sensor;
                            $suhuSensors++;
                        } elseif (str_contains($value2->flag_sensor, 'kelembaban')) {
                            $currentKelembaban += $latestData->nilai_sensor;
                            $kelembabanSensors++;
                        }
                    }

                    $avgCalculation = count($nilaiSensorTemp) > 0
                        ? number_format(array_sum($nilaiSensorTemp) / count($nilaiSensorTemp), 1)
                        : 0;

                    array_push($dataSensor, [
                        'type' => 'sensor',
                        'flag_sensor' => $value2->flag_sensor,
                        'value' => $nilaiSensorTemp,
                        'avg' => $avgCalculation,
                        'stddev' => $stddevTemp,
                    ]);

                    array_push($dataWaktuSensor, [
                        'type' => 'waktu',
                        'flag_sensor' => $value2->flag_sensor,
                        'value' => $waktuSensorTemp,
                    ]);

                    $nilaiSensorTemp = [];
                    $waktuSensorTemp = [];
                    $stddevTemp = [];
                }
            }
        }

        return [
            'statusRuangan' => $statusRuangan,
            'dataSensor' => $dataSensor,
            'dataWaktuSensor' => $dataWaktuSensor,
            'currentSuhu' => $suhuSensors > 0 ? number_format($currentSuhu / $suhuSensors, 2) : 0,
            'currentKelembaban' => $kelembabanSensors > 0 ? number_format($currentKelembaban / $kelembabanSensors, 2) : 0,
        ];
    }

    /**
     * Get sensor data for Drying Room (non-blower sensors)
     */

    public function getPengeringanData(string $gudangId)
    {
        $dataSensor = [];
        $dataWaktuSensor = [];
        $dataGudang = GudangModel::findOrFail($gudangId);
        $dataRuangan = $dataGudang->getDataRuangan;

        $nilaiSensorTemp = [];
        $waktuSensorTemp = [];
        $stddevTemp = [];

        $statusRuangan = [];
        $listBlower = [];

        $currentSuhu = 0;
        $currentKelembaban = 0;
        $suhuSensors = 0;
        $kelembabanSensors = 0;

        $selectRawQuery = '
            DATE(created_at) as tgl,
            HOUR(created_at) as jam,
            FLOOR(MINUTE(created_at)/15) as menit_group,
            AVG(nilai_sensor) as avg_nilai,
            MIN(created_at) as waktu_asli,
            STDDEV_SAMP(nilai_sensor) as stddev,
            MIN(nilai_sensor) as min_nilai,
            MAX(nilai_sensor) as max_nilai
        ';

        $dateNow = '%' . date('Y-m-d') . '%';

        foreach ($dataRuangan as $value) {
            if ($value->tipe_ruangan == 3) {
                $statusRuangan = $value->status_ruangan;

                foreach ($value->getDataSensor as $value2) {
                    if (str_contains($value2->flag_sensor, 'blower')) {
                        $listBlower[] = [
                            'id_sensor' => $value2->id_sensor,
                            'flag_sensor' => $value2->flag_sensor,
                        ];
                        continue;
                    }

                    $groupedData = $value2->getDataNilaiSensor()
                        ->selectRaw($selectRawQuery)
                        ->where('created_at', 'LIKE', $dateNow)
                        ->groupBy('tgl', 'jam', 'menit_group')
                        ->orderBy('waktu_asli', 'DESC')
                        ->limit($this->LIMIT)
                        ->get();

                    foreach ($groupedData as $value3) {
                        $nilaiSensorTemp[] = number_format($value3->avg_nilai, 2);
                        $waktuSensorTemp[] = date('G:i', Carbon::parse($value3->waktu_asli)->timestamp);
                        $stddevTemp[] = [Carbon::parse($value3->waktu_asli)->valueOf(), number_format($value3->stddev, 2)];
                    }

                    $latestData = $value2->getDataNilaiSensor()
                        ->where('created_at', 'LIKE', $dateNow)
                        ->orderBy('created_at', 'DESC')
                        ->first();

                    if ($latestData) {
                        if (str_contains($value2->flag_sensor, 'suhu')) {
                            $currentSuhu += $latestData->nilai_sensor;
                            $suhuSensors++;
                        } elseif (str_contains($value2->flag_sensor, 'kelembaban')) {
                            $currentKelembaban += $latestData->nilai_sensor;
                            $kelembabanSensors++;
                        }
                    }

                    $avgCalculation = count($nilaiSensorTemp) > 0
                        ? number_format(array_sum($nilaiSensorTemp) / count($nilaiSensorTemp), 1)
                        : 0;

                    array_push($dataSensor, [
                        'type' => 'sensor',
                        'flag_sensor' => $value2->flag_sensor,
                        'value' => $nilaiSensorTemp,
                        'avg' => $avgCalculation,
                        'stddev' => $stddevTemp,
                    ]);

                    array_push($dataWaktuSensor, [
                        'type' => 'waktu',
                        'flag_sensor' => $value2->flag_sensor,
                        'value' => $waktuSensorTemp,
                    ]);

                    $nilaiSensorTemp = [];
                    $waktuSensorTemp = [];
                    $stddevTemp = [];
                }
            }
        }

        return [
            'status' => true,
            'statusRuangan' => $statusRuangan,
            'listBlower' => $listBlower,
            'dataSensor' => $dataSensor,
            'dataWaktuSensor' => $dataWaktuSensor,
            'currentSuhu' => $suhuSensors > 0 ? number_format($currentSuhu / $suhuSensors, 2) : 0,
            'currentKelembaban' => $kelembabanSensors > 0 ? number_format($currentKelembaban / $kelembabanSensors, 2) : 0,
        ];
    }
    // public function getPengeringanData(string $gudangId)
    // {
    //         $dataSensor = [];
    //         $dataWaktuSensor = [];
    //         $dataGudang = GudangModel::findOrFail($gudangId);
    //         $dataRuangan = $dataGudang->getDataRuangan;
    //         $nilaiSensorTemp = [];
    //         $waktuSensorTemp = [];
    //         $stddevTemp = [];
    //         $statusRuangan = [];
    //         $currentSuhu = 0;
    //         $currentKelembaban = 0;
    //         $suhuSensors = 0;
    //         $kelembabanSensors = 0;
    //         $listBlower = [];

    //         $selectRawQuery = '
    //         DATE(created_at) as tgl,
    //         HOUR(created_at) as jam,
    //         FLOOR(MINUTE(created_at)/15) as menit_group,
    //         AVG(nilai_sensor) as avg_nilai,
    //         MIN(created_at) as waktu_asli,
    //         STDDEV_SAMP(nilai_sensor) as stddev,
    //         MIN(nilai_sensor) as min_nilai,
    //         MAX(nilai_sensor) as max_nilai
    //     ';

    //         foreach ($dataRuangan as $value) {
    //             if ($value->tipe_ruangan == 3) {
    //                 $statusRuangan = $value->status_ruangan;
    //                 foreach ($value->getDataSensor as $value2) {
    //                     if (str_contains($value2->flag_sensor, 'blower')) {
    //                         $listBlower[] = [
    //                             'id_sensor' => $value2->id_sensor,
    //                             'flag_sensor' => $value2->flag_sensor,
    //                         ];

    //                         continue;
    //                     }

    //                     $dateNow = '%'.date('Y-m-d').'%';
    //                     $usedDate = date('Y-m-d');

    //                     if ($value2->getDataNilaiSensor()->where('created_at', 'LIKE', $dateNow)->get()->isEmpty()) {
    //                         $temp = $value2->getDataNilaiSensor()->orderBy('created_at', 'DESC')->first();
    //                         if ($temp) {
    //                             $usedDate = Carbon::parse($temp->created_at)->format('Y-m-d');
    //                             $dateNow = '%'.$usedDate.'%';
    //                         }
    //                     }

    //                     $groupedData = $value2->getDataNilaiSensor()
    //                         ->selectRaw($selectRawQuery)
    //                         ->where('created_at', 'LIKE', $dateNow)
    //                         ->groupBy('tgl', 'jam', 'menit_group')
    //                         ->orderBy('waktu_asli', 'DESC')
    //                         ->limit($this->LIMIT)
    //                         ->get();

    //                     foreach ($groupedData as $value3) {
    //                         $nilaiSensorTemp[] = number_format($value3->avg_nilai, 2);
    //                         $waktuSensorTemp[] = date('G:i', Carbon::parse($value3->waktu_asli)->timestamp);
    //                         $stddevTemp[] = [Carbon::parse($value3->waktu_asli)->valueOf(), number_format($value3->stddev, 2)];
    //                     }

    //                     $latestData = $value2->getDataNilaiSensor()
    //                         ->where('created_at', 'LIKE', $dateNow)
    //                         ->orderBy('created_at', 'DESC')
    //                         ->first();

    //                     if ($latestData) {
    //                         if (str_contains($value2->flag_sensor, 'suhu')) {
    //                             $currentSuhu += (int) $latestData->nilai_sensor;
    //                             $suhuSensors++;
    //                         } elseif (str_contains($value2->flag_sensor, 'kelembaban')) {
    //                             $currentKelembaban += (int) $latestData->nilai_sensor;
    //                             $kelembabanSensors++;
    //                         }
    //                     }

    //                     array_push($dataSensor, [
    //                         'type' => 'sensor',
    //                         'flag_sensor' => $value2->flag_sensor,
    //                         'value' => $nilaiSensorTemp,
    //                         'avg' => count($nilaiSensorTemp) > 0 ? number_format(array_sum($nilaiSensorTemp) / count($nilaiSensorTemp), 1) : 0,
    //                         'stddev' => $stddevTemp,
    //                     ]);

    //                     array_push($dataWaktuSensor, [
    //                         'type' => 'waktu',
    //                         'flag_sensor' => $value2->flag_sensor,
    //                         'value' => $waktuSensorTemp,
    //                     ]);

    //                     $nilaiSensorTemp = [];
    //                     $waktuSensorTemp = [];
    //                     $stddevTemp = [];
    //                 }
    //             }
    //         }

    //         return [
    //             'status' => true,
    //             'statusRuangan' => $statusRuangan,
    //             'listBlower' => $listBlower,
    //             'dataSensor' => $dataSensor,
    //             'dataWaktuSensor' => $dataWaktuSensor,
    //             'currentSuhu' => $suhuSensors > 0 ? number_format($currentSuhu / $suhuSensors, 2) : 0,
    //             'currentKelembaban' => $kelembabanSensors > 0 ? number_format($currentKelembaban / $kelembabanSensors, 2) : 0,
    //         ];
    // }
    //     $dataSensor = [];
    //     $dataWaktuSensor = [];
    //     $dataGudang = GudangModel::findOrFail($gudangId);
    //     $dataRuangan = $dataGudang->getDataRuangan;
    //     $nilaiSensorTemp = [];
    //     $waktuSensorTemp = [];
    //     $stddevTemp = [];
    //     $statusRuangan = [];

    //     $currentSuhu = 0;
    //     $currentKelembaban = 0;
    //     $suhuSensors = 0;
    //     $kelembabanSensors = 0;

    //     $selectRawQuery = '
    //         DATE(created_at) as tgl,
    //         HOUR(created_at) as jam,
    //         FLOOR(MINUTE(created_at)/15) as menit_group,
    //         AVG(nilai_sensor) as avg_nilai,
    //         MIN(created_at) as waktu_asli,
    //         STDDEV_SAMP(nilai_sensor) as stddev,
    //         MIN(nilai_sensor) as min_nilai,
    //         MAX(nilai_sensor) as max_nilai
    //     ';

    //     $dateNow = '%' . date('Y-m-d') . '%';

    //     foreach ($dataRuangan as $value) {
    //         if ($value->tipe_ruangan == 3) {
    //             $statusRuangan = $value->status_ruangan;

    //             foreach ($value->getDataSensor as $value2) {


    //                 $groupedData = $value2->getDataNilaiSensor()
    //                     ->selectRaw($selectRawQuery)
    //                     ->where('created_at', 'LIKE', $dateNow)
    //                     ->groupBy('tgl', 'jam', 'menit_group')
    //                     ->orderBy('waktu_asli', 'DESC')
    //                     ->limit($this->LIMIT)
    //                     ->get();

    //                 foreach ($groupedData as $value3) {
    //                     $nilaiSensorTemp[] = number_format($value3->avg_nilai, 2);
    //                     $waktuSensorTemp[] = date('G:i', Carbon::parse($value3->waktu_asli)->timestamp);
    //                     $stddevTemp[] = [Carbon::parse($value3->waktu_asli)->valueOf(), number_format($value3->stddev, 2)];
    //                 }

    //                 $latestData = $value2->getDataNilaiSensor()
    //                     ->where('created_at', 'LIKE', $dateNow)
    //                     ->orderBy('created_at', 'DESC')
    //                     ->first();

    //                 if ($latestData) {
    //                     if (str_contains($value2->flag_sensor, 'suhu')) {
    //                         $currentSuhu += $latestData->nilai_sensor;
    //                         $suhuSensors++;
    //                     } elseif (str_contains($value2->flag_sensor, 'kelembaban')) {
    //                         $currentKelembaban += $latestData->nilai_sensor;
    //                         $kelembabanSensors++;
    //                     }
    //                 }

    //                 $avgCalculation = count($nilaiSensorTemp) > 0
    //                     ? number_format(array_sum($nilaiSensorTemp) / count($nilaiSensorTemp), 1)
    //                     : 0;

    //                 array_push($dataSensor, [
    //                     'type' => 'sensor',
    //                     'flag_sensor' => $value2->flag_sensor,
    //                     'value' => $nilaiSensorTemp,
    //                     'avg' => $avgCalculation,
    //                     'stddev' => $stddevTemp,
    //                 ]);

    //                 array_push($dataWaktuSensor, [
    //                     'type' => 'waktu',
    //                     'flag_sensor' => $value2->flag_sensor,
    //                     'value' => $waktuSensorTemp,
    //                 ]);

    //                 $nilaiSensorTemp = [];
    //                 $waktuSensorTemp = [];
    //                 $stddevTemp = [];
    //             }
    //         }
    //     }

    //     return [
    //         'statusRuangan' => $statusRuangan,
    //         'dataSensor' => $dataSensor,
    //         'dataWaktuSensor' => $dataWaktuSensor,
    //         'currentSuhu' => $suhuSensors > 0 ? number_format($currentSuhu / $suhuSensors, 2) : 0,
    //         'currentKelembaban' => $kelembabanSensors > 0 ? number_format($currentKelembaban / $kelembabanSensors, 2) : 0,
    //     ];
    // }

    /**
     * Get blower data for Drying Room
     */
    public function getDataStatusBlower(string $sensorId)
    {
        $sensor = SensorModel::with('getDataNilaiBlower')->findOrFail($sensorId);

        if (!$sensor->getDataNilaiBlower) {
            return [
                'status' => false,
                'msg' => 'Data blower tidak ditemukan',
            ];
        }

        return [
            'status' => true,
            'data' => [
                'id_sensor' => $sensor->id_sensor,
                'nilai_sensor' => $sensor->getDataNilaiBlower->nilai_sensor,
                'is_active' => $sensor->getDataNilaiBlower->nilai_sensor == '1',
            ],
        ];
    }

    /**
     * Toggle blower status
     */
    public function toggleBlower(string $sensorId)
    {
        $modeBlower = ModeBlowerModel::firstOrCreate(
            ['id_sensor' => $sensorId],
            ['nilai_sensor' => '0']
        );

        $newValue = ($modeBlower->nilai_sensor == '1') ? '0' : '1';

        $modeBlower->update([
            'nilai_sensor' => $newValue,
        ]);

        return [
            'status' => true,
            'msg' => 'Alat blower berhasil diubah!',
            'nilai_baru' => $newValue,
        ];
    }
}
