<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameScore extends Model
{
    protected $table = 'game_score';
    protected $fillable = ['user_id', 'score', 'answered_questions'];


    public function users()
    {
        return $this->belongsTo(User::class);
    }
    
    protected $casts = [
        'answered_questions' => 'array',
    ];
}
