<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GameRoom extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'user_id_created', 'expiration_date', 'status'];

    protected $casts = [
        'expiration_date' => 'datetime',
        'status' => 'boolean',
    ];

    protected $hidden = ['updated_at'];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function scores()
    {
        return $this->hasMany(GameScore::class, 'game_room_id');
    }
}
