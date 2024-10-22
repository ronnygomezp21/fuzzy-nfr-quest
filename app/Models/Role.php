<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name', 'description'];
    protected $hidden = ['id', 'created_at', 'updated_at', 'status'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
