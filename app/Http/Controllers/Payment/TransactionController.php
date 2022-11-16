<?php

namespace App\Http\Controllers\Payment;

use App\Models\User;
use App\Models\CoinFillRequest;
use App\Traits\Auth\ApiResponse;
use Illuminate\Http\UploadedFile;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Api\Auth\Payment\PaymentDepositRequest;
use App\Http\Requests\Api\Auth\Payment\PaymentWithdrawRequest;
use App\Models\WithdrawRequest;

class TransactionController extends Controller
{
    use ApiResponse;

    public function deposit(PaymentDepositRequest $request)
    {
        $user = User::find(auth()->user()->id);

        CoinFillRequest::create([
            "user_id" => $user->id,
            "payment_type_id" => $request->payment_type_id,
            "amount" => $request->amount,
            "transaction_time" => $request->transaction_datetime,
        ]);

        $this->uploadScreenshot(image: $request->file('transaction_ss'), user: $user, status: 'deposit');

        return $this->responseSucceed(message: 'Success');
    }

    public function withdraw(PaymentWithdrawRequest $request)
    {
        $user = User::find(auth()->user()->id);

        WithdrawRequest::create([
            "user_id" => $user->id,
            "payment_type_id" => $request->payment_type_id,
            "amount" => $request->amount,
            "transaction_time" => $request->transaction_datetime,
        ]);

        $this->uploadScreenshot(image: $request->file('transaction_ss'), user: $user, status: 'withdraw');

        return $this->responseSucceed(message: 'Success');
    }

    public function uploadScreenshot(UploadedFile $image, User $user, string $status)
    {
        $path = $user->reference_id . '/' . $status;
        Storage::put($path, $image);
    }
}
