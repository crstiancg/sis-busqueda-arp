<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroVerificacion extends Model
{
    use HasFactory;
    protected $table = 'registro_verificacions';
    protected $with = ['registroBusqueda','user'];
    protected $fillable = [
        'RB_id_derivado',
        'user_id',
        'estado',
        'observaciones',
    ];
    public function registroBusqueda()
    {
        return $this->belongsTo(RegistroBusqueda::class, 'RB_id_derivado');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
