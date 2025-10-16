<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SensorModel extends Model
{
    protected $table = "sensor";
    protected $primaryKey = "id_sensor";
    protected $keyType = "string";
    public $incrementing = false;

    protected $fillable = [
        'id_sensor',
        'flag_sensor',
        'id_ruangan',
    ];

    protected $casts = [
        'id_sensor' => 'string',
        'id_ruangan' => 'string',
        
    ];

    protected static function boot() {
        parent::boot();

        static::creating(function($model) {
            if(empty($model->id_sensor)) {
                $model->id_sensor = Str::uuid();
            }
        });
    }

    public function getDataRuangan() {
        return $this->belongsTo(RuanganModel::class, 'id_ruangan', 'id_ruangan');
    }

    public function getDataNilaiSensor() {
        return $this->hasMany(NilaiSensorModel::class, 'id_sensor', 'id_sensor');
    }
}
