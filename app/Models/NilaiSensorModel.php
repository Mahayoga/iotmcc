<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class NilaiSensorModel extends Model
{
    /** @use HasFactory<\Database\Factories\NilaiSensorModelFactory> */
    use HasFactory;


    protected $table = "nilai_sensor";
    protected $primaryKey = "id_nilai_sensor";
    protected $keyType = "string";
    public $incrementing = false;

    protected $fillable = [
        'id_nilai_sensor',
        'id_sensor',
    ];

    protected $casts = [
        'id_nilai_sensor' => 'string',
        'id_sensor' => 'string',
        
    ];

    protected static function boot() {
        parent::boot();

        static::creating(function($model) {
            if(empty($model->id_nilai_sensor)) {
                $model->id_nilai_sensor = Str::uuid();
            }
        });
    }

    public function getDataSensor() {
        return $this->belongsTo(SensorModel::class, 'id_sensor', 'id_sensor');
    }

    public function getDataLogSensor() {
        return $this->hasMany(LogSensorModel::class,'id_nilai_sensor','id_nilai_sensor');
    }
}
