<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ModePengeringanModel extends Model
{
    protected $table = "mode_pengeringan";
    protected $primaryKey = "id_mode_pengeringan";
    protected $keyType = "string";
    public $incrementing = false;

    protected $fillable = [
        'id_mode_pengeringan',
        'mode_pengeringan',
        'min_suhu',
        'max_suhu',
        'min_kelembaban',
        'max_kelembaban',
        'id_ruangan'
    ];

    protected static function boot() {
        parent::boot();

        static::creating(function($model) {
            if(empty($model->id_mode_pengeringan)) {
                $model->id_mode_pengeringan = Str::uuid();
            }
        });
    }

    public function getDataRuangan() {
        return $this->belongsTo(RuanganModel::class, 'id_ruangan', 'id_ruangan');
    }
}
