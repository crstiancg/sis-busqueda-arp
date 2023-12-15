<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Escritura extends Model
{
    use HasFactory;
    protected $table = 'escrituras';
    protected $fillable = [
        'bien',
        'subserie_id',
        'fecha',
        'cod_escritura',
        'cod_folioInicial',
        'cod_folioFinal',
        'libro_id',
    ];
    public function subSerie()
    {
        return $this->belongsTo(SubSerie::class, 'subserie_id');
    }
    public function libro()
    {
        return $this->belongsTo(Libro::class, 'libro_id');
    }

    public function favorecidos()
    {
        return $this->hasMany(Favorecido::class, 'escritura_id');
    }
    public function otorgantes()
    {
        return $this->hasMany(Otorgante::class, 'escritura_id');
    }
}
