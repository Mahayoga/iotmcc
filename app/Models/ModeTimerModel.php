<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ModeTimerModel extends Model
{
    /** @use HasFactory<\Database\Factories\NilaiSensorModelFactory> */
    use HasFactory;


    protected $table = "mode_timer";
    protected $primaryKey = "id_mode_timer";
    protected $keyType = "string";
    public $incrementing = false;

    protected $fillable = [
        'limit_timer',
        'id_sensor',
    ];

    protected $casts = [
        'id_mode_timer' => 'string',
        'id_sensor' => 'string',
        
    ];

    protected static function boot() {
        parent::boot();

        static::creating(function($model) {
            if(empty($model->id_mode_timer)) {
                $model->id_mode_timer = Str::uuid();
            }
        });
    }

    public function getDataTimer() {
        return $this->belongsTo(SensorModel::class, 'id_sensor', 'id_sensor');
    }

    public function getDataLogSensor() {
        return $this->hasMany(LogSensorModel::class,'id_nilai_sensor','id_mode_timer');
    }
}
