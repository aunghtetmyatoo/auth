<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmountCoinLog extends Model
{
    use HasFactory;

    protected $table = "amount_coin_logs";

    protected $fillable = [
        "user_id",
        "transaction_type_id",
        "amount",
        "coin",
    ];
}
