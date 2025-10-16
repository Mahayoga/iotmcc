<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class RuanganModel extends Model
{
    protected $table = "ruangan";
    protected $primaryKey = "id_ruangan";
    protected $keyType = "string";
    public $incrementing = false;

    protected $fillable = [
        'nama_ruangan',
        'tipe_ruangan',
        'status_ruangan',
        'id_gudang'
    ];

    protected $casts = [
        'id_ruangan' => 'string',
        'id_gudang' => 'string'
    ];

    protected static function boot() {
        parent::boot();

        static::creating(function($model) {
            if(empty($model->id_ruangan)) {
                $model->id_ruangan = Str::uuid();
            }
        });
    }

    public function getDataGudang() {
        return $this->belongsTo(GudangModel::class, 'id_gudang', 'id_gudang');
    }

    public function getDataSensor() {
        return $this->hasMany(SensorModel::class, 'id_ruangan', 'id_ruangan');
    }
}
