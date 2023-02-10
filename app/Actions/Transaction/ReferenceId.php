<?php

namespace App\Actions\Transaction;

class ReferenceId
{
    public function execute(string $prefix, string $uuid, int $pad_length = 15)
    {
        return $prefix .
            sprintf("%0{$pad_length}d", $uuid) .
            rand(pow(10, 2 - 1), pow(10, 2) - 1);
    }
}
