<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ModeBlowerModel extends Model
{
    protected $table = "mode_blower";
    protected $primaryKey = "id_mode_blower";
    protected $keyType = "string";
    public $incrementing = false;

    protected $fillable = [
        'nilai_sensor',
        'id_sensor',
    ];

    protected $casts = [
        'id_mode_blower' => 'string',
        'id_sensor' => 'string',
        
    ];

    protected static function boot() {
        parent::boot();

        static::creating(function($model) {
            if(empty($model->id_mode_blower)) {
                $model->id_mode_blower = Str::uuid();
            }
        });
    }

    public function getDataSensor() {
        return $this->belongsTo(SensorModel::class, 'id_sensor', 'id_sensor');
    }

    public function getDataLogModeBlower() {
        return $this->hasMany(LogModeBlowerModel::class,'id_mode_blower','id_mode_blower');
    }
}
