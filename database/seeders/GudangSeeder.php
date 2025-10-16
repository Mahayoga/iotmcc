<?php

namespace Database\Seeders;

use App\Models\GudangModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GudangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GudangModel::create([
            'id_gudang' => '11dc76a4-3c99-4563-9bbe-e1916a4a4ff2',
            'nama_gudang' => 'Gudang Vanili Rembangan',
            'lokasi_gudang' => 'Rembangan',
            'status_gudang' => 1,
        ]);
    }
}
