<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

class RiwayatNotifikasi extends Model
{
    use HasFactory, Prunable;

    protected $table = 'riwayat_notifikasi';

    protected $fillable = [
        'id_sensor',
        'title',
        'body',
        'kategori',
    ];

    public function prunable()
    {

        return static::where('created_at', '<=', now()->subDays(7));
    }
}
