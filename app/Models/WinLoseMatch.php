<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WinLoseMatch extends Model
{
    use HasFactory;

    protected $table = "win_lose_matches";

    protected $fillable = [
        'user_id',
        'game_type_id',
        'win_match',
        'loss_match',
        'total_match',
        'bet_coin',
        'win_coin',
        'loss_coin',
        'privacy',
    ];

}
