<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NilaiTimerModel extends Model
{
    /** @use HasFactory<\Database\Factories\NilaiSensorModelFactory> */
    use HasFactory;


    protected $table = "nilai_timer";
    protected $primaryKey = "id_nilai_timer";
    protected $keyType = "string";
    public $incrementing = false;

    protected $fillable = [
        'flag_timer',
        'nilai_timer',
        'rssi',
        'snr',
        'id_sensor',
    ];

    protected $casts = [
        'id_nilai_timer' => 'string',
        'id_sensor' => 'string',
        
    ];

    protected static function boot() {
        parent::boot();

        static::creating(function($model) {
            if(empty($model->id_nilai_timer)) {
                $model->id_nilai_timer = Str::uuid();
            }
        });
    }

    public function getDataTimer() {
        return $this->belongsTo(SensorModel::class, 'id_sensor', 'id_sensor');
    }

    public function getDataLogSensor() {
        return $this->hasMany(LogSensorModel::class,'id_nilai_sensor','id_nilai_timer');
    }
}
