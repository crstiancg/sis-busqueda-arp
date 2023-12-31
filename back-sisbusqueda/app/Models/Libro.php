<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    use HasFactory;
    protected $table = 'libros';
    // protected $casts = [
    //     'created_at' => 'datetime:d.m.Y H:i',
    //     'updated_at' => 'datetime:d.m.Y H:i',
    // ];
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'protocolo',
        'notario_id',
        'updated_at',
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
