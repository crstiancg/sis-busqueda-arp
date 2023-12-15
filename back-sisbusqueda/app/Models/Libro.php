<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    use HasFactory;
    protected $table = 'libros';
    protected $fillable = [
        'nombre',
        'fecha',
        'protocolo',
        'notario_id',
    ];
    public function notario()
    {
        return $this->belongsTo(Notario::class, 'notario_id');
    }
    public function escrituras()
    {
        return $this->belongsTo(Escritura::class, 'libro_id');
    }
}
