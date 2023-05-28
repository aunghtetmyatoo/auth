<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerSetting extends Model
{
    use HasFactory;

    protected $table = 'player_settings';

    protected $fillable = ['user_id', 'game_type_id', 'sound_status', 'vibration_status', 'challenge_status', 'friend_status'];
}
