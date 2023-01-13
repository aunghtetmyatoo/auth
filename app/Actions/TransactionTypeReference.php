<?php

namespace App\Actions;

class TransactionTypeReference
{
    public function execute(string $prefix): string
    {
        $now = now();
        return strtoupper($prefix)
            . $now->year
            . $now->month
            . $now->day
            . $now->hour
            . $now->minute
            . $now->second
            . mt_rand(1000, 9999);
    }
}
