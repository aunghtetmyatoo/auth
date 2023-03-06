<?php

namespace App\Actions;

use App\Constants\Coin;
use App\Constants\Status;

class ConvertCoinAmount
{
    public function handle(int $currency, $condition)
    {
        if ($condition === Status::CONVERT_COIN) {
            return $currency / Coin::ONECOIN;
        }

        if ($condition === Status::CONVERT_AMOUNT) {
            return $currency * Coin::ONECOIN;
        }
    }
}
