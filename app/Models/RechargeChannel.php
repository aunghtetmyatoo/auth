<?php

namespace App\Models;

use App\Models\ExchangeCurrency;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RechargeChannel extends Model
{
    use HasFactory;

    protected $table = 'recharge_channels';

    protected $fillable = ['name', 'exchange_currency_id', 'telegram_channel_id'];

    public function exchange_currency()
    {
        return $this->belongsTo(ExchangeCurrency::class);
    }

    public function recharge_request()
    {
        return $this->hasMany(RechargeRequest::class);
    }
}
