<?php

namespace App\Exceptions;

use Exception;
use App\Traits\Auth\ApiResponse;

class GameCoinNotEnoughException extends Exception
{
    use ApiResponse;

    public function render()
    {
        return $this->responseUnprocessableEntity(
            message: $this->getMessage() ? $this->getMessage() : 'game.not_enough.coin'
        );
    }
}
