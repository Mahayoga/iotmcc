<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Str;

class LogModeBlowerModel extends Model
{
    protected $table = "log_mode_blower";
    protected $primaryKey = "id_log_mode";
    protected $keyType = "string";
    public $incrementing = false;

    protected $fillable = [
        'log_level',
        'nilai_sensor',
        'id_mode_blower',
    ];

    protected $casts = [
        'id_log_mode' => 'string',
        'id_mode_blower' => 'string',
        
    ];

    protected static function boot() {
        parent::boot();

        static::creating(function($model) {
            if(empty($model->id_log_mode)) {
                $model->id_log_mode = Str::uuid();
            }
        });
    }

    public function getDataModeBlower() {
        return $this->belongsTo(ModeBlowerModel::class, 'id_mode_blower', 'id_mode_blower');
    }
}
