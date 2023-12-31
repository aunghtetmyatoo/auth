<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\Payment\BankAccountRequest;
use App\Models\User;
use App\Traits\Auth\ApiResponse;

class BankAccountController extends Controller
{
    use ApiResponse;

    public function addPaymentMethod(BankAccountRequest $request)
    {
        $user = User::find(auth()->user()->id);
        $user->update([
            "payment_account_number" => $request->bank_account_number,
            "payment_account_name" => $request->bank_account_name,
            "payment_types_id" => $request->payment_type,
        ]);
        return $this->responseSucceed(message: 'Success');
    }
}
