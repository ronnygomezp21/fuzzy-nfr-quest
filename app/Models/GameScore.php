<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class GameScore extends Model
{
    use HasFactory;

    protected $table = 'game_score';
    protected $fillable = ['user_id', 'game_room_id', 'score', 'answered_questions', 'duration'];
    protected $hidden = ['updated_at'];


    public function users()
    {
        return $this->belongsTo(User::class);
    }
    
    protected $casts = [
        'answered_questions' => 'array',
    ];

    public function gameRoom()
    {
        return $this->belongsTo(GameRoom::class, 'game_room_id');
    }
}
