<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExchangeCurrency extends Model
{
    use HasFactory;

    protected $table = 'exchange_currencies';

    protected $fillable = ['name', 'sign', 'buy_rate', 'sell_rate'];

    public function recharge_channel()
    {
        return $this->hasMany(RechargeChannel::class);
    }
}
