<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otorgante extends Model
{
    use HasFactory;
    protected $table = 'otorgantes';
    protected $fillable = [
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'nombre_completo',
        'escritura_id',
    ];
    public function escritura()
    {
        return $this->belongsTo(Escritura::class, 'escritura_id');
    }
}
