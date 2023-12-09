<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sia extends Model
{
    use HasFactory;
    protected $table = 'sia';
    protected $fillable = [
        'notario',
        'otorgante',
        'favorecido',
        'fecha',
        'serie',
        'folio',
        'escritura',
        'bien',
    ];
}
