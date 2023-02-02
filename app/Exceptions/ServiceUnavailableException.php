<?php

namespace App\Exceptions;

use App\CustomFunctions\ResponseHelpers;

use Exception;

class ServiceUnavailableException extends Exception
{
    public function render()
    {
        return ResponseHelpers::customResponse(422, 'kdjfldjf');
    }
}
