<?php

namespace App\Actions;

use Illuminate\Support\Str;

class GenerateReferenceId
{
    public function execute(): string
    {
        return  strtoupper(Str::random(3)) . get_random_digit(6);
    }
}
