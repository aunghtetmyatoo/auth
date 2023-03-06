<?php

namespace App\Exceptions;

use Exception;
use App\Traits\Auth\ApiResponse;

class UserNotExistException extends Exception
{
    use ApiResponse;

    public function render()
    {
        return $this->responseUnauthenticated(
            message: $this->getMessage() ? $this->getMessage() : 'auth.invalid.user'
        );
    }
}
