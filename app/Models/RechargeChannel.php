<?php

namespace App\Models;

use App\Models\ExchangeCurrency;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RechargeChannel extends Model
{
    use HasFactory;

    public function exchange_currency()
    {
        return $this->belongsTo(ExchangeCurrency::class);
    }
}
