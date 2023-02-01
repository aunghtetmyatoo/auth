<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameType extends Model
{
    use HasFactory;

    protected $table = "game_types";

    protected $fillable = ["name"];

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function game_category()
    {
        return $this->belongsTo(GameCategory::class);
    }
}
