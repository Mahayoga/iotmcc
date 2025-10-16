<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NilaiSensorModel>
 */
class NilaiSensorModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $idSensor = [
            '4519cc50-56ae-4e94-90b0-b17f2c5b4cf5',
            '4519cc50-56ae-4e94-90b0-b17f2c5b4cf6',
            '4519cc50-56ae-4e94-90b0-b17f2c5b4cf7',
            '4519cc50-56ae-4e94-90b0-b17f2c5b4cf8',
            '4519cc50-56ae-4e94-90b0-b17f2c5b4cf9',
            '4519cc50-56ae-4e94-90b0-b17f2c5b4c10',
            '4519cc50-56ae-4e94-90b0-b17f2c5b4c11',
            '4519cc50-56ae-4e94-90b0-b17f2c5b4c12',
            '4519cc50-56ae-4e94-90b0-b17f2c5b4c13',
            '4519cc50-56ae-4e94-90b0-b17f2c5b4c14',
            '4519cc50-56ae-4e94-90b0-b17f2c5b4cy6'
        ];

        return [
            'id_nilai_sensor' => Str::uuid(),
            'nilai_sensor' => random_int(0, 100),
            'id_sensor' => $idSensor[random_int(0, count($idSensor) - 1)],
        ];
    }
}
