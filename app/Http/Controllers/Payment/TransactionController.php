<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Auth\ApiResponse;
use App\Http\Requests\Api\Auth\Payment\DepositRequest;
use App\Models\User;
use Carbon\Carbon;

class TransactionController extends Controller
{
    use ApiResponse;

    public function deposit(DepositRequest $request)
    {
        $user = User::find(auth()->user()->id);
        $user->coin_fill_requests()->create([
            "transaction_screenshot" => $request->transaction_ss,
            "payment_type_id" => $request->payment_type_id,
            "amount" => $request->amount,
            "transaction_time" => $request->transaction_datetime,
        ]);
        return $this->responseSucceed(message: 'Success');
    }
}
