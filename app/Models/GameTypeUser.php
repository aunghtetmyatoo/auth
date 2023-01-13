<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameTypeUser extends Model
{
    use HasFactory;

    protected $table = 'game_type_user';

    protected $fillable = ['user_id', 'game_type_id', 'coin'];
}
