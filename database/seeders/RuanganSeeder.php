<?php

namespace Database\Seeders;

use App\Models\RuanganModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RuanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RuanganModel::create([
            'id_ruangan' => '93b4a0ae-90bd-4c77-89d5-5544eaefa0c5',
            'nama_ruangan' => 'Ruangan Perebusan',
            'tipe_ruangan' => 1,
            'status_ruangan' => 1,
            'id_gudang' => '11dc76a4-3c99-4563-9bbe-e1916a4a4ff2',
        ]);

        RuanganModel::create([
            'id_ruangan' => '93b4a0ae-90bd-4c77-89d5-5544eaefa0c6',
            'nama_ruangan' => 'Ruangan Fermentasi',
            'tipe_ruangan' => 2,
            'status_ruangan' => 1,
            'id_gudang' => '11dc76a4-3c99-4563-9bbe-e1916a4a4ff2',
        ]);

        RuanganModel::create([
            'id_ruangan' => '93b4a0ae-90bd-4c77-89d5-5544eaefa0c7',
            'nama_ruangan' => 'Ruangan Pengeringan',
            'tipe_ruangan' => 3,
            'status_ruangan' => 1,
            'id_gudang' => '11dc76a4-3c99-4563-9bbe-e1916a4a4ff2',
        ]);
    }
}
