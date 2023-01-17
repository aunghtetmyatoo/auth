<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WinLossMatch extends Model
{
    use HasFactory;

    protected $table = "win_loss_matches";

    protected $fillable = [
        'user_id',
        'game_type_id',
        'win_match',
        'loss_match',
        'total_match',
        'bet_amount',
        'bet_coin',
        'win_coin',
        'loss_coin',
    ];

}
