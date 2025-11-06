<?php

namespace Database\Seeders;

use App\Models\SensorModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SensorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Ruangan 1 (Perebusan)
        SensorModel::create([
            'id_sensor' => '4519cc50-56ae-4e94-90b0-b17f2c5b4cf5',
            'flag_sensor' => 'suhu_1',
            'id_ruangan' => '93b4a0ae-90bd-4c77-89d5-5544eaefa0c5',
        ]);
        SensorModel::create([
            'id_sensor' => '4519cc50-56ae-4e94-90b0-b17f2c5b4cy6',
            'flag_sensor' => 'suhu_2',
            'id_ruangan' => '93b4a0ae-90bd-4c77-89d5-5544eaefa0c5',
        ]);
        SensorModel::create([
            'id_sensor' => '4519cc50-56ae-4e94-90b0-b17f2c5b4cf6',
            'flag_sensor' => 'timer_1',
            'id_ruangan' => '93b4a0ae-90bd-4c77-89d5-5544eaefa0c5',
        ]);

        // Ruangan 2 (Fermentasi)
        SensorModel::create([
            'id_sensor' => '4519cc50-56ae-4e94-90b0-b17f2c5b4cf7',
            'flag_sensor' => 'suhu_1',
            'id_ruangan' => '93b4a0ae-90bd-4c77-89d5-5544eaefa0c6',
        ]);
        SensorModel::create([
            'id_sensor' => '4519cc50-56ae-4e94-90b0-b17f2c5b4cf8',
            'flag_sensor' => 'kelembaban_1',
            'id_ruangan' => '93b4a0ae-90bd-4c77-89d5-5544eaefa0c6',
        ]);
        SensorModel::create([
            'id_sensor' => '4519cc50-56ae-4e94-90b0-b17f2c5b4cf9',
            'flag_sensor' => 'suhu_2',
            'id_ruangan' => '93b4a0ae-90bd-4c77-89d5-5544eaefa0c6',
        ]);
        SensorModel::create([
            'id_sensor' => '4519cc50-56ae-4e94-90b0-b17f2c5b4c10',
            'flag_sensor' => 'kelembaban_2',
            'id_ruangan' => '93b4a0ae-90bd-4c77-89d5-5544eaefa0c6',
        ]);

        // Ruangan 3 (Pengeringan)
        SensorModel::create([
            'id_sensor' => '4519cc50-56ae-4e94-90b0-b17f2c5b4c11',
            'flag_sensor' => 'suhu_1',
            'id_ruangan' => '93b4a0ae-90bd-4c77-89d5-5544eaefa0c7',
        ]);
        SensorModel::create([
            'id_sensor' => '4519cc50-56ae-4e94-90b0-b17f2c5b4c12',
            'flag_sensor' => 'kelembaban_1',
            'id_ruangan' => '93b4a0ae-90bd-4c77-89d5-5544eaefa0c7',
        ]);
        SensorModel::create([
            'id_sensor' => '4519cc50-56ae-4e94-90b0-b17f2c5b4c13',
            'flag_sensor' => 'suhu_2',
            'id_ruangan' => '93b4a0ae-90bd-4c77-89d5-5544eaefa0c7',
        ]);
        SensorModel::create([
            'id_sensor' => '4519cc50-56ae-4e94-90b0-b17f2c5b4c14',
            'flag_sensor' => 'kelembaban_2',
            'id_ruangan' => '93b4a0ae-90bd-4c77-89d5-5544eaefa0c7',
        ]);
        SensorModel::create([
            'id_sensor' => '4519cc50-56ae-4e94-90b0-b17f2c5b4c15',
            'flag_sensor' => 'blower_1',
            'id_ruangan' => '93b4a0ae-90bd-4c77-89d5-5544eaefa0c7',
        ]);
        SensorModel::create([
            'id_sensor' => '4519cc50-56ae-4e94-90b0-b17f2c5b4c16',
            'flag_sensor' => 'blower_2',
            'id_ruangan' => '93b4a0ae-90bd-4c77-89d5-5544eaefa0c7',
        ]);
    }
}
