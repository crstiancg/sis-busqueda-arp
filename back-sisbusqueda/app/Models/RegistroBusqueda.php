<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroBusqueda extends Model
{
    use HasFactory;
    protected $table = 'registro_busquedas';
    protected $fillable = [
        'RS_id_derivado',
        'user_id',
        'estado',
        'cod_protocolo',
        'cod_escritura',
        'cod_folioInicial',
        'cod_folioFinal',
        'observaciones',
    ];
    public function registroBusqueda()
    {
        return $this->belongsTo(RegistroBusqueda::class, 'RS_id_derivado');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function registroVerificacion()
    {
        return $this->hasOne(RegistroVerificacion::class, 'RB_id_derivado');
    }
}
