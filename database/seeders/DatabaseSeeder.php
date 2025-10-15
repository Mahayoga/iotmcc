<?php

namespace Database\Seeders;

use App\Models\GudangModel;
use App\Models\NilaiSensorModel;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => password_hash('admin1234', PASSWORD_BCRYPT, ['cost' => 12])
        ]);


        User::factory()->create([
            'name' => 'User Lab KSi',
            'email' => 'Labksisteam@gmail.com',
            'password' => password_hash('admin1234', PASSWORD_BCRYPT, ['cost' => 12])
        ]);

        $this->call(GudangSeeder::class);
        $this->call(RuanganSeeder::class);
        $this->call(SensorSeeder::class);
        NilaiSensorModel::factory(100)->create();
        $this->call(ModeBlowerSeeder::class);
    }
}
