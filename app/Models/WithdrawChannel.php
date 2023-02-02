<?php

namespace App\Models;

use App\Models\ExchangeCurrency;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WithdrawChannel extends Model
{
    use HasFactory;

    protected $table = 'withdraw_channels';

    protected $fillable = ['name', 'exchange_currency_id', 'telegram_channel_id'];

    public function withdraw_request()
    {
        return $this->hasMany(WithdrawRequest::class);
    }

    public function exchange_currency()
    {
        return $this->belongsTo(ExchangeCurrency::class);
    }
}
