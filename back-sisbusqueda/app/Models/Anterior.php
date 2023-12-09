<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anterior extends Model
{
    use HasFactory;

    protected $table = 'anterior';
    protected $fillable = [
        'notario',
        'lugar',
        'subserie',
        'fecha',
        'bien',
        'protocolo',
        'nescritura',
        'folio',
        'cfolio',
        'observacion',
        'trabajador',
        'otorgantes',
        'favorecidos',
        'juriotorgantes',
        'jurifavorecidos',
    ];
}
