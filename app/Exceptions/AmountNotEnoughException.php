<?php

namespace App\Exceptions;

use App\Traits\Auth\ApiResponse;
use Exception;

class AmountNotEnoughException extends Exception
{
    use ApiResponse;

    public function render()
    {
        return $this->responseUnprocessableEntity(
            message: $this->getMessage() ? $this->getMessage() : 'Amount Not Enough.'
        );
    }
}
