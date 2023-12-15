<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroSolicitud extends Model
{
    use HasFactory;
    protected $table = 'registro_solicituds';
    protected $fillable = [
        'solicitud_id',
        'user_id',
        'estado',
    ];
    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class, 'solicitud_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function registroBusqueda()
    {
        return $this->hasOne(RegistroBusqueda::class, 'RS_id_derivado');
    }
}
