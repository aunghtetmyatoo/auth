<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameCategory extends Model
{
    use HasFactory;

    protected $table = 'game_categories';

    protected $fillable = ["name"];

    protected $hidden = ["created_at", "updated_at"];

    public function posts()
    {
        return $this->hasMany(GameType::class);
    }

    public function gameTypes()
    {
        return $this->hasMany(GameType::class);
    }
}
