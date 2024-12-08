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
        'other_recommended_values',
        'recomend',
        'feedback3',
        'validar',
        'game_room_id'
        //'sala_de_juego'
    ];

    public function gameRoom()
    {
        return $this->belongsTo(GameRoom::class);
    }
}
