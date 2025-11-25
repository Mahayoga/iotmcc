<?php

namespace App\Http\Controllers;

use App\Models\NilaiSensorModel;
use App\Models\RiwayatNotifikasi;
use App\Models\SensorModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class NilaiSensorAPIController extends Controller
{
    protected $messaging;

    public function __construct(Messaging $messaging)
    {
        $this->messaging = $messaging;
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
        try {
            if (! $request->id_sensor || ! isset($request->nilai_sensor)) {
                return response()->json(['status' => false, 'msg' => 'Data tidak lengkap']);
            }

            $dataSensor = SensorModel::with('getDataRuangan')->find($request->id_sensor);

            if (! $dataSensor) {
                return response()->json(['status' => false, 'msg' => 'Sensor tidak ditemukan']);
            }

            $namaRuangan = Str::lower($dataSensor->getDataRuangan->nama_ruangan ?? '');
            $flagSensor = Str::lower($dataSensor->flag_sensor ?? '');
            $nilai = (float) $request->nilai_sensor;

            NilaiSensorModel::create([
                'id_sensor' => $request->id_sensor,
                'nilai_sensor' => $request->nilai_sensor,
                'rssi' => $request->rssi,
                'snr' => $request->snr,
            ]);

            try {
                $notif = ['kirim' => false, 'title' => '', 'body' => ''];
                $isSuhu = str_contains($flagSensor, 'suhu');
                $isLembab = str_contains($flagSensor, 'kelembaban');

                if (str_contains($namaRuangan, 'perebusan')) {
                    if ($isSuhu) {
                        if ($nilai >= 100) {
                            $notif = [
                                'kirim' => true,
                                'title' => 'BAHAYA: PEREBUSAN MENDIDIH',
                                'body' => "Suhu mencapai {$nilai}°C. Matikan alat blanching!",
                            ];
                        } elseif ($nilai >= 50) {
                            $notif = [
                                'kirim' => true,
                                'title' => 'Peringatan: Perebusan Panas',
                                'body' => "Suhu perebusan tinggi ({$nilai}°C). Harap pantau.",
                            ];
                        } elseif ($nilai > 30) {
                            $notif = [
                                'kirim' => true,
                                'title' => 'Info: Perebusan Hangat',
                                'body' => "Suhu naik ke {$nilai}°C.",
                            ];
                        } elseif ($nilai <= 20) {
                            $notif = [
                                'kirim' => true,
                                'title' => 'Peringatan: Perebusan Dingin',
                                'body' => "Suhu drop ke {$nilai}°C. Cek alat blanching.",
                            ];
                        }
                    }
                } elseif (str_contains($namaRuangan, 'fermentasi')) {
                    if ($isSuhu) {
                        if ($nilai >= 60) {
                            $notif = [
                                'kirim' => true,
                                'title' => 'KRITIS: FERMENTASI KEPANASAN',
                                'body' => "Suhu ruangan {$nilai}°C segera cek ruangan!",
                            ];
                        } elseif ($nilai > 45) {
                            $notif = [
                                'kirim' => true,
                                'title' => 'Warning: Fermentasi Mulai Panas',
                                'body' => "Suhu ruangan {$nilai}°C, mendekati batas kritis.",
                            ];
                        } elseif ($nilai <= 20) {
                            $notif = [
                                'kirim' => true,
                                'title' => 'Fermentasi Kedinginan',
                                'body' => "Suhu ruangan {$nilai}°C, proses fermentasi terhenti.",
                            ];
                        }
                    } elseif ($isLembab) {
                        if ($nilai < 50) {
                            $notif = [
                                'kirim' => true,
                                'title' => 'Fermentasi Kering',
                                'body' => "Kelembaban drop {$nilai}%. Cek ruangan.",
                            ];
                        }
                    }
                } elseif (str_contains($namaRuangan, 'pengeringan')) {
                    if ($isSuhu) {
                        if ($nilai >= 70) {
                            $notif = [
                                'kirim' => true,
                                'title' => 'BAHAYA: PENGERINGAN EKSTREM',
                                'body' => "Suhu ruangan {$nilai}°C. Segera cek ruangan!",
                            ];
                        } elseif ($nilai >= 50) {
                            $notif = [
                                'kirim' => true,
                                'title' => 'Peringatan Suhu Pengeringan',
                                'body' => "Suhu ruangan pengeringan ({$nilai}°C). mendekati batas kritis.",
                            ];
                        } elseif ($nilai <= 20) {
                            $notif = [
                                'kirim' => true,
                                'title' => 'Peringatan Suhu Pengeringan Rendah',
                                'body' => "Suhu drop ke {$nilai}°C. Proses pengeringan lambat.",
                            ];
                        }
                    } elseif ($isLembab) {
                        if ($nilai > 80) {
                            $notif = [
                                'kirim' => true,
                                'title' => 'Peringatan Kelembaban Tinggi',
                                'body' => "Kelembaban pengeringan ({$nilai}%). Cek ruangan",
                            ];
                        }
                    }
                }

                if ($notif['kirim']) {
                    $durasiCache = 300;
                    $cacheKey = 'alert_sent_'.$request->id_sensor.'_'.md5($notif['title']);

                    if (! Cache::has($cacheKey)) {

                        $this->kirimNotifFCM($notif['title'], $notif['body']);

                        $kategori = 'info';
                        $judulLower = Str::lower($notif['title']);
                        if (str_contains($judulLower, 'bahaya') || str_contains($judulLower, 'kritis')) {
                            $kategori = 'danger';
                        } elseif (str_contains($judulLower, 'peringatan') || str_contains($judulLower, 'warning')) {
                            $kategori = 'warning';
                        }

                        RiwayatNotifikasi::create([
                            'id_sensor' => $request->id_sensor,
                            'title' => $notif['title'],
                            'body' => $notif['body'],
                            'kategori' => $kategori,
                        ]);

                        Cache::put($cacheKey, true, $durasiCache);

                        \Log::info("Notif Dikirim: {$notif['title']} | Next alert in: {$durasiCache}s");
                    }
                }

            } catch (\Exception $errNotif) {
                \Log::error('Gagal logic notifikasi: '.$errNotif->getMessage());
            }

            return response()->json([
                'status' => true,
                'msg' => 'Data berhasil ditambahkan!',
                'request_data' => $request->all(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => 'Ada kesalahan server!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Kirim notifikasi FCM
     */
    private function kirimNotifFCM($title, $body)
    {
        $tokens = User::whereNotNull('fcm_token')->pluck('fcm_token')->toArray();

        if (empty($tokens)) {
            return;
        }

        try {
            $notification = Notification::create($title, $body);

            $message = CloudMessage::new()
                ->withNotification($notification)
                ->withData([
                    'type' => 'alert_sensor',
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                ]);

            $this->messaging->sendMulticast($message, $tokens);
        } catch (\Exception $e) {
            \Log::error('Gagal kirim multicast: '.$e->getMessage());
        }
    }

    /**
     * Display riwayat notifikasi.
     */
    public function getRiwayatNotifikasi()
    {
        $data = RiwayatNotifikasi::orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
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
