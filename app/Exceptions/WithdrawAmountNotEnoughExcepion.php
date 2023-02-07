<?php

namespace App\Exceptions;

use App\CustomFunctions\ResponseHelpers;
use Exception;

class WithdrawAmountNotEnoughExcepion extends Exception
{
    public function render(){
        return ResponseHelpers::customResponse(422,__('withdraw.balance_not_enough'));
    }
}
