<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'win_streak',
        'privacy',
        'handle_win_rate',
        'win_rate',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
