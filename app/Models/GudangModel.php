<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class GudangModel extends Model
{
    /** @use HasFactory<\Database\Factories\GudangFactory> */
    use HasFactory;

    protected $table = "gudang";
    protected $primaryKey = "id_gudang";
    protected $keyType = "string";
    public $incrementing = false;

    protected $fillable = [
        'nama_gudang',
        'lokasi_gudang',
        'status_gudang',
    ];

    protected $casts = [
        'id_gudang' => 'string',
    ];

    protected static function boot() {
        parent::boot();

        static::creating(function($model) {
            if(empty($model->id_gudang)) {
                $model->id_gudang = Str::uuid();
            }
        });
    }

    public function getDataRuangan() {
        return $this->hasMany(RuanganModel::class, 'id_gudang', 'id_gudang');
    }
}
