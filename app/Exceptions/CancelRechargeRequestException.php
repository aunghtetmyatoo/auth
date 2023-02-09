<?php

namespace App\Exceptions;

use App\CustomFunctions\ResponseHelpers;
use Exception;

class CancelRechargeRequestException extends Exception
{
    public function render(){
        return ResponseHelpers::customResponse(422,__('channels/recharge.confirmed'));
    }
}
