<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Str;

class LogSensorModel extends Model
{
    protected $table = "log_sensor";
    protected $primaryKey = "id_log_sensor";
    protected $keyType = "string";
    public $incrementing = false;

    protected $fillable = [
        'flag_log_sensor',
        'log_level',
        'nilai_sensor',
        'id_nilai_sensor',
    ];

    protected $casts = [
        'id_log_sensor' => 'string',
        'id_nilai_sensor' => 'string',
        
    ];

    protected static function boot() {
        parent::boot();

        static::creating(function($model) {
            if(empty($model->id_log_sensor)) {
                $model->id_log_sensor = Str::uuid();
            }
        });
    }

    public function getDataNilaiSensor() {
        return $this->belongsTo(NilaiSensorModel::class, 'id_nilai_sensor', 'id_nilai_sensor');
    }
}
