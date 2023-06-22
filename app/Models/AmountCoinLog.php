<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmountCoinLog extends Model
{
    use HasFactory, Uuid;

    protected $table = "amount_coin_logs";

    protected $fillable = [
        "user_id",
        "transaction_type_id",
        "amount",
        "coin",
    ];
}
