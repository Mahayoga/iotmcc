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
        $statusRuangan = null;
        $suhuTotal = 0;
        $totalDataSuhu = 0;

        $selectRawQuery = '
        DATE(created_at) as tgl,
        HOUR(created_at) as jam,
        FLOOR(MINUTE(created_at)/15) as menit_group,
        AVG(nilai_sensor) as avg_nilai,
        MIN(created_at) as waktu_asli,
        MIN(nilai_sensor) as min_nilai,
        MAX(nilai_sensor) as max_nilai
    ';

        foreach ($dataRuangan as $value) {
            if ($value->tipe_ruangan == 1) {
                $statusRuangan = $value->status_ruangan;

                foreach ($value->getDataSensor as $value2) {
                    if (str_contains($value2->flag_sensor, 'timer')) {
                        continue;
                    }

                    $dateNow = '%'.date('Y-m-d').'%';
                    $usedDate = date('Y-m-d');

                    if ($value2->getDataNilaiSensor()->where('created_at', 'LIKE', $dateNow)->get()->isEmpty()) {
                        $temp = $value2->getDataNilaiSensor()->orderBy('created_at', 'DESC')->first();
                        if ($temp) {
                            $usedDate = Carbon::parse($temp->created_at)->format('Y-m-d');
                            $dateNow = '%'.$usedDate.'%';
                        }
                    }

                    $groupedData = $value2->getDataNilaiSensor()
                        ->selectRaw($selectRawQuery)
                        ->where('created_at', 'LIKE', $dateNow)
                        ->groupBy('tgl', 'jam', 'menit_group')
                        ->orderBy('waktu_asli', 'DESC')
                        ->limit($this->LIMIT)
                        ->get();

                    foreach ($groupedData as $value3) {
                        $avgNilai = number_format($value3->avg_nilai, 2);
                        $nilaiSensorTemp[] = $avgNilai;
                        $waktuSensorTemp[] = date('G:i', Carbon::parse($value3->waktu_asli)->timestamp);

                        if (str_contains($value2->flag_sensor, 'suhu')) {
                            $suhuTotal += floatval($avgNilai);
                            $totalDataSuhu++;
                        }
                    }

                    array_push($dataSensor, [
                        'type' => 'sensor',
                        'flag_sensor' => $value2->flag_sensor,
                        'value' => $nilaiSensorTemp,
                        'avg' => count($nilaiSensorTemp) > 0 ? number_format(array_sum($nilaiSensorTemp) / count($nilaiSensorTemp), 1) : 0,
                    ]);

                    array_push($dataWaktuSensor, [
                        'type' => 'waktu',
                        'flag_sensor' => $value2->flag_sensor,
                        'value' => $waktuSensorTemp,
                    ]);

                    $nilaiSensorTemp = [];
                    $waktuSensorTemp = [];
                }
                break;
            }
        }

        $rataRataSuhu = $totalDataSuhu > 0 ? number_format($suhuTotal / $totalDataSuhu, 2) : 0;

        return [
            'statusRuangan' => $statusRuangan,
            'dataSensor' => $dataSensor,
            'dataWaktuSensor' => $dataWaktuSensor,
            'rataRataSuhu' => $rataRataSuhu,
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

    //                 $dateNow = '%'.date('Y-m-d').'%';
    //                 if ($value2->getDataNilaiSensor()->where('created_at', 'LIKE', $dateNow)->get()->isEmpty()) {
    //                     $temp = $value2->getDataNilaiSensor()->orderBy('created_at', 'DESC')->limit(1)->get();
    //                     if (! $temp->isEmpty()) {
    //                         $dateNow = '%'.date('Y-m-d', Carbon::parse($temp[0]->created_at)->timestamp).'%';
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

                        if ($nilaiTimer->flag_timer == 'start') {
                            $dataTimer = [
                                'status' => true,
                                'status_timer' => 'stop',
                                'sisa_timer' => number_format((float) $nilaiTimer->nilai_timer - microtime(true), 2),
                            ];
                            NilaiTimerModel::create([
                                'flag_timer' => 'stop',
                                'nilai_timer' => microtime(true),
                                'id_sensor' => $sensor->id_sensor,
                                'rssi' => 0,
                                'snr' => 0,
                            ]);
                        } elseif ($nilaiTimer->flag_timer == 'stop') {
                            NilaiTimerModel::create([
                                'flag_timer' => 'start',
                                'nilai_timer' => microtime(true),
                                'id_sensor' => $sensor->id_sensor,
                                'rssi' => 0,
                                'snr' => 0,
                            ]);
                            $dataTimer = [
                                'status' => true,
                                'status_timer' => 'start',
                                'sisa_timer' => number_format((float) $nilaiTimer->nilai_timer - microtime(true), 2),
                            ];
                        }
                        // Return immediately after handling one timer toggle per request usually?
                        // Or if logic requires handling specific timer passed via param, needs adjustment.
                        // Based on original code, it loops but effectively returns on first match or last.
                        // We assume the request intends to toggle relevant timers.
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

                        $sisaTimer = microtime(true) - (float) $nilaiTimer->nilai_timer;

                        if ($nilaiTimer->flag_timer == 'stop') {
                            $dataTimer[] = [
                                'flag_sensor' => $sensor->flag_sensor,
                                'flag_timer' => $nilaiTimer->flag_timer,
                                'nilai_timer' => $nilaiTimer?->nilai_timer ?? 0,
                                'limit_timer' => $modeTimer?->limit_timer ?? null,
                                'sisa_timer' => 0,
                                'updated_at' => $nilaiTimer?->created_at?->format('Y-m-d H:i:s'),
                            ];

                            continue;
                        }

                        if ((float) $sisaTimer > (float) $modeTimer->limit_timer) {
                            $sisaTimer = 0;
                            if ($nilaiTimer->flag_timer == 'start') {
                                NilaiTimerModel::create([
                                    'flag_timer' => 'stop',
                                    'nilai_timer' => microtime(true),
                                    'id_sensor' => $sensor->id_sensor,
                                    'rssi' => 0,
                                    'snr' => 0,
                                ]);

                                $nilaiTimer = NilaiTimerModel::where('id_sensor', $sensor->id_sensor)
                                    ->orderBy('created_at', 'desc')->first();
                            }
                        }

                        $dataTimer[] = [
                            'flag_sensor' => $sensor->flag_sensor,
                            'flag_timer' => $nilaiTimer->flag_timer,
                            'nilai_timer' => $nilaiTimer?->nilai_timer ?? 0,
                            'limit_timer' => $modeTimer?->limit_timer ?? 0,
                            'sisa_timer' => $sisaTimer,
                            'updated_at' => $nilaiTimer?->created_at?->format('Y-m-d H:i:s'),
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
                        // Note: Original code had logic to check start/stop but didn't actually execute the create model.
                        // It only updated the limit.
                    }
                }
            }
        }

        return $found;
    }

    /**
     * Get sensor data for Fermentation Room with averaging logic
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

        foreach ($dataRuangan as $value) {
            if ($value->tipe_ruangan == 2) {
                $statusRuangan = $value->status_ruangan;
                foreach ($value->getDataSensor as $value2) {
                    $dateNow = '%'.date('Y-m-d').'%';
                    $usedDate = date('Y-m-d');

                    if ($value2->getDataNilaiSensor()->where('created_at', 'LIKE', $dateNow)->get()->isEmpty()) {
                        $temp = $value2->getDataNilaiSensor()->orderBy('created_at', 'DESC')->first();
                        if ($temp) {
                            $usedDate = Carbon::parse($temp->created_at)->format('Y-m-d');
                            $dateNow = '%'.$usedDate.'%';
                        }
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
                            $currentSuhu += (int) $latestData->nilai_sensor;
                            $suhuSensors++;
                        } elseif (str_contains($value2->flag_sensor, 'kelembaban')) {
                            $currentKelembaban += (int) $latestData->nilai_sensor;
                            $kelembabanSensors++;
                        }
                    }

                    array_push($dataSensor, [
                        'type' => 'sensor',
                        'flag_sensor' => $value2->flag_sensor,
                        'value' => $nilaiSensorTemp,
                        'avg' => count($nilaiSensorTemp) > 0 ? number_format(array_sum($nilaiSensorTemp) / count($nilaiSensorTemp), 1) : 0,
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
    // public function getPengeringanData(string $gudangId)
    // {
    //     $dataSensor = [];
    //     $dataWaktuSensor = [];
    //     $dataGudang = GudangModel::findOrFail($gudangId);
    //     $dataRuangan = $dataGudang->getDataRuangan;
    //     $nilaiSensorTemp = [];
    //     $waktuSensorTemp = [];
    //     $stddevTemp = [];
    //     $statusRuangan = [];
    //     $currentSuhu = null;
    //     $currentKelembaban = null;
    //     $listBlower = [];

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

    //     foreach ($dataRuangan as $value) {
    //         if ($value->tipe_ruangan == 3) {
    //             $statusRuangan = $value->status_ruangan;
    //             foreach ($value->getDataSensor as $value2) {
    //                 if (str_contains($value2->flag_sensor, 'blower')) {
    //                     $listBlower[] = [
    //                         'id_sensor' => $value2->id_sensor,
    //                         'flag_sensor' => $value2->flag_sensor,
    //                     ];

    //                     continue;
    //                 }
    //                 $dateNow = '%'.date('Y-m-d').'%';
    //                 if ($value2->getDataNilaiSensor()->where('created_at', 'LIKE', $dateNow)->get()->isEmpty()) {
    //                     $temp = $value2->getDataNilaiSensor()->orderBy('created_at', 'DESC')->limit(1)->get();
    //                     if (! $temp->isEmpty()) {
    //                         $dateNow = '%'.date('Y-m-d', Carbon::parse($temp[0]->created_at)->timestamp).'%';
    //                     }
    //                 }

    //                 foreach ($value2->getDataNilaiSensor()
    //                     ->selectRaw($selectRawQuery)
    //                     ->where('created_at', 'LIKE', $dateNow)
    //                     ->groupBy('tgl', 'jam', 'menit_group')
    //                     ->orderBy('waktu_asli', 'DESC')
    //                     ->limit($this->LIMIT)
    //                     ->get() as $value3) {
    //                     $nilaiSensorTemp[] = number_format($value3->avg_nilai, 2);
    //                     $waktuSensorTemp[] = date('G:i', Carbon::parse($value3->waktu_asli)->timestamp);
    //                     $stddevTemp[] = [Carbon::parse($value3->waktu_asli)->valueOf(), number_format($value3->stddev, 2)];
    //                 }

    //                 foreach ($value2->getDataNilaiSensor()
    //                     ->where('created_at', 'LIKE', $dateNow)
    //                     ->orderBy('created_at', 'DESC')
    //                     ->limit(1)
    //                     ->get() as $value3) {
    //                     if (str_contains($value2->flag_sensor, 'suhu')) {
    //                         $currentSuhu += (int) $value3->nilai_sensor;
    //                     } elseif (str_contains($value2->flag_sensor, 'kelembaban')) {
    //                         $currentKelembaban += (int) $value3->nilai_sensor;
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
    //         'status' => true,
    //         'statusRuangan' => $statusRuangan,
    //         'listBlower' => $listBlower,
    //         'dataSensor' => $dataSensor,
    //         'dataWaktuSensor' => $dataWaktuSensor,
    //         'currentSuhu' => $currentSuhu !== null ? number_format($currentSuhu / 2, 2) : 0,
    //         'currentKelembaban' => $currentKelembaban !== null ? number_format($currentKelembaban / 2, 2) : 0,
    //     ];
    // }

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
        $currentSuhu = 0;
        $currentKelembaban = 0;
        $suhuSensors = 0;
        $kelembabanSensors = 0;
        $listBlower = [];

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

                    $dateNow = '%'.date('Y-m-d').'%';
                    $usedDate = date('Y-m-d');

                    if ($value2->getDataNilaiSensor()->where('created_at', 'LIKE', $dateNow)->get()->isEmpty()) {
                        $temp = $value2->getDataNilaiSensor()->orderBy('created_at', 'DESC')->first();
                        if ($temp) {
                            $usedDate = Carbon::parse($temp->created_at)->format('Y-m-d');
                            $dateNow = '%'.$usedDate.'%';
                        }
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
                            $currentSuhu += (int) $latestData->nilai_sensor;
                            $suhuSensors++;
                        } elseif (str_contains($value2->flag_sensor, 'kelembaban')) {
                            $currentKelembaban += (int) $latestData->nilai_sensor;
                            $kelembabanSensors++;
                        }
                    }

                    array_push($dataSensor, [
                        'type' => 'sensor',
                        'flag_sensor' => $value2->flag_sensor,
                        'value' => $nilaiSensorTemp,
                        'avg' => count($nilaiSensorTemp) > 0 ? number_format(array_sum($nilaiSensorTemp) / count($nilaiSensorTemp), 1) : 0,
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

    /**
     * Get blower data for Drying Room
     */
    public function getDataStatusBlower(string $sensorId)
    {
        $sensor = SensorModel::with('getDataNilaiBlower')->findOrFail($sensorId);

        if (! $sensor->getDataNilaiBlower) {
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

    /**
     * Calculate averages for sensor data
     */
    private function calculateAverages(array $allSensorData)
    {
        $averagedValues = [];

        // Group sensors by type
        $suhuSensors = array_filter($allSensorData, function ($key) {
            return str_contains($key, 'suhu');
        }, ARRAY_FILTER_USE_KEY);

        $kelembabanSensors = array_filter($allSensorData, function ($key) {
            return str_contains($key, 'kelembaban');
        }, ARRAY_FILTER_USE_KEY);

        // Calculate averages for each data point
        for ($i = 0; $i < 11; $i++) {
            // Temperature averaging
            $suhuAvg = 0;
            $suhuCount = 0;
            $suhuTime = '00:00:00';

            foreach ($suhuSensors as $sensorData) {
                if (isset($sensorData['values'][$i])) {
                    $suhuAvg += $sensorData['values'][$i];
                    $suhuCount++;
                    $suhuTime = $sensorData['waktu'][$i];
                }
            }

            if ($suhuCount > 0) {
                $averagedValues['suhu']['values'][] = number_format($suhuAvg / $suhuCount, 1);
                $averagedValues['suhu']['waktu'][] = $suhuTime;
            }

            // Humidity averaging
            $kelembabanAvg = 0;
            $kelembabanCount = 0;
            $kelembabanTime = '00:00:00';

            foreach ($kelembabanSensors as $sensorData) {
                if (isset($sensorData['values'][$i])) {
                    $kelembabanAvg += $sensorData['values'][$i];
                    $kelembabanCount++;
                    $kelembabanTime = $sensorData['waktu'][$i];
                }
            }

            if ($kelembabanCount > 0) {
                $averagedValues['kelembaban']['values'][] = number_format($kelembabanAvg / $kelembabanCount, 1);
                $averagedValues['kelembaban']['waktu'][] = $kelembabanTime;
            }
        }

        return $averagedValues;
    }

    /**
     * Calculate global averages
     */
    private function calculateGlobalAverages(array $averagedValues)
    {
        $globalAverages = [];

        if (isset($averagedValues['suhu']['values'])) {
            $suhuValues = array_map('floatval', $averagedValues['suhu']['values']);
            $globalAverages['suhu'] = number_format(array_sum($suhuValues) / count($suhuValues), 1);
        }

        if (isset($averagedValues['kelembaban']['values'])) {
            $kelembabanValues = array_map('floatval', $averagedValues['kelembaban']['values']);
            $globalAverages['kelembaban'] = number_format(array_sum($kelembabanValues) / count($kelembabanValues), 1);
        }

        return $globalAverages;
    }

    /**
     * Calculate blower active duration
     */
    private function calculateBlowerDuration(array $dataBlower, array $dataWaktuBlower)
    {
        $durasiAktif = 0;
        $lastOnTime = null;

        $reversedDataBlower = array_reverse($dataBlower);
        $reversedWaktuBlower = array_reverse($dataWaktuBlower);

        foreach ($reversedDataBlower as $i => $nilai) {
            $time = $reversedWaktuBlower[$i];
            if ($nilai == 1) {
                if ($lastOnTime === null) {
                    $lastOnTime = $time;
                }
            } elseif ($nilai == 0 && $lastOnTime) {
                $durasiAktif += (strtotime($time) - strtotime($lastOnTime));
                $lastOnTime = null;
            }
        }

        if ($lastOnTime) {
            $durasiAktif += (time() - strtotime($lastOnTime));
        }

        return $durasiAktif;
    }
}
