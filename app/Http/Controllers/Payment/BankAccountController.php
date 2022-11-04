<?php

namespace App\Http\Controllers\Payment;

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
        $result = checkUserStatus(user: $user, device_id: $request->device_id, status: "CHECK_DEVICE");

        if ($result["status"] == false) {
            if ($result["message"] != null) {
                return $this->responseSomethingWentWrong(message: $result["message"]);
            }
            return $this->responseSomethingWentWrong();
        }

        $user->payment_account_number = $request->bank_account_number;
        $user->payment_account_name = $request->bank_account_name;
        $user->payment_types_id = $request->payment_type;
        $user->save();
        return $this->responseSucceed(message: 'Success');
    }
}
