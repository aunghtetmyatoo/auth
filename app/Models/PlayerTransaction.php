<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerTransaction extends Model
{
    use HasFactory, Uuid;

    protected $table = 'player_transactions';

    protected $fillable = ['reference_id', 'player_id', 'banker_id', 'coin', 'game_type_id', 'game_match_id'];

    public function histories()
    {
        return $this->morphMany(History::class, 'historiable');
    }

    public function transactionable()
    {
        return $this->morphMany(History::class, 'transactionable');
    }

    public function history()
    {
        return $this->belongsTo(History::class);
    }
}
