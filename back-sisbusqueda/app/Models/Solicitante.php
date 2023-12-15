<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitante extends Model
{
    use HasFactory;
    protected $table = 'solicitantes';
    protected $fillable = [
        'nombre_completo',
        'correo',
        'celular',
    ];
    public function solicitudes()
    {
        return $this->hasMany(Solicitud::class, 'solicitante_id');
    }
}
