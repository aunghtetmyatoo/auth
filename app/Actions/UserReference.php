<?php

namespace App\Actions;

class UserReference
{
    public function execute(string $prefix, string $phone_number): string
    {
        $now = now();
        return $prefix
            . substr($phone_number, 2)
            . 'D'
            . $now->year
            . $now->month
            . $now->day
            . $now->hour
            . $now->minute
            . $now->second;
    }
}
