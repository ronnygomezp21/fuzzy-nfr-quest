<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameRoom extends Model
{
    protected $fillable = ['code'];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
