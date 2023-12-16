<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    use HasFactory;
    protected $table = 'solicituds';
    protected $fillable = [
        'notario_id',
        'subserie_id',
        'solicitante_id',
        'otorgantes',
        'favorecidos',
        'fecha',
        'bien',
        'mas_datos',
        'tipo_copia',
        'ubigeo_cod',
        'estado',
    ];
    public function notario()
    {
        return $this->belongsTo(Notario::class, 'notario_id');
    }
    public function SubSerie()
    {
        return $this->belongsTo(SubSerie::class, 'subserie_id');
    }
    public function solicitante()
    {
        return $this->belongsTo(Solicitante::class, 'solicitante_id');
    }
    public function registroSolicitud()
    {
        return $this->hasOne(RegistroSolicitud::class, 'solicitud_id');
    }

    public function ubigeo()
    {
        return $this->belongsTo(Ubigeo::class, 'ubigeo_cod','codigo');
    }
}
