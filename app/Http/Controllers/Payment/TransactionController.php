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
        // dd(Carbon::now()->toDateTimeString());
        $user = User::find(auth()->user()->id);
        $payment_type_id = $request->payment_type_id;
        $amount = $request->amount;
        $transaction_datetime = $request->transaction_datetime;
        $transaction_ss = $request->transaction_ss;

        $user->coin_fill_requests()->create([
            "transaction_screenshot" => $transaction_ss,
            "payment_type_id" => $payment_type_id,
            "amount" => $amount,
        ]);
        return $this->responseSucceed(message: 'Success');
    }
}
