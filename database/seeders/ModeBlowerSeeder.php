<?php

namespace Database\Seeders;

use App\Models\ModeBlowerModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Str;

class ModeBlowerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ModeBlowerModel::create([
            'id_mode_blower' => Str::uuid(),
            'nilai_sensor' => (string) random_int(0, 1),
            'id_sensor' => '4519cc50-56ae-4e94-90b0-b17f2c5b4c15',
        ]);
        ModeBlowerModel::create([
            'id_mode_blower' => Str::uuid(),
            'nilai_sensor' => (string) random_int(0, 1),
            'id_sensor' => '4519cc50-56ae-4e94-90b0-b17f2c5b4c16',
        ]);
    }
}
