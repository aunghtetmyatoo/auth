<?php

namespace App\Exceptions;

use Exception;

class UnprocessableException extends Exception
{
    public function __construct(public array $errors)
    {
    }
}
