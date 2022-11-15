<?php

namespace App\Actions;

class PaymentTypeReference
{
    public function execute(string $prefix): string
    {
        return $prefix . get_random_digit(3);
    }
}
