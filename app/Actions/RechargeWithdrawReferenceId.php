<?php

namespace App\Actions;

use Illuminate\Support\Str;

class RechargeWithdrawReferenceId
{
    public function execute(string $prefix, int $digit, int $pad_length = 15)
    {
        return $prefix .
            sprintf("%0{$pad_length}d", $digit) .
            rand(pow(10, 2 - 1), pow(10, 2) - 1);
    }
}
