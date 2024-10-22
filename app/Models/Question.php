<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable
 = [
        'nfr',
        'variable',
        'feedback1',
        'value',
        'feedback2',
        'recomend',
        'feedback3',
        'validar',
        'sala_de_juego'
    ];
}