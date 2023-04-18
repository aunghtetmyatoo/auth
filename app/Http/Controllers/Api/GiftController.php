<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use App\Models\User;
use App\Models\Friend;
use App\Models\History;
use App\Constants\Status;
use Illuminate\Http\Request;
use App\Constants\ServerPath;
use App\Actions\Endpoint;
use App\Models\TransactionType;
use App\Exceptions\GeneralError;
use App\Traits\Auth\ApiResponse;
use Illuminate\Support\Facades\DB;
use App\Enums\TransactionType as EnumTransactionType;
use App\Http\Requests\Api\GiftRequest\BuyGiftRequest;
use App\Http\Requests\Api\GiftRequest\GiveGiftRequest;

class GiftController extends Controller
{
    use ApiResponse;

    public function __construct(private Endpoint $endpoint)
    {
    }

    public function buyGift(BuyGiftRequest $request)
    {
        $transaction_type_id = TransactionType::where('name', EnumTransactionType::Gift)->pluck('id')->first();

        DB::beginTransaction();
        try {
            $user = User::lockForUpdate()->where('id', auth()->user()->id)->first();
            $response = $this->endpoint->handle(
                config('api.url.card'),
                ServerPath::BUY_GIFT,
                [
                    'user_id' => auth()->user()->id,
                    'user_model' => get_class(auth()->user()),
                    'transaction_type_id' => $transaction_type_id,
                    'type' => $request->type,
                    'amount' => $request->amount,
                    'user_amount_before_transaction' => $user->amount,
                    'store_id' => $request->store_id,
                    'encrypt' => $request->encrypt
                ]
            );
            if ($response['data']['message'] == 'Success') {
                if (!($request->type == Status::STICKER)) {
                    User::where('id', auth()->user()->id)
                        ->update(['amount' => $user->amount - $request->amount]);
                }
            } else {
                throw new GeneralError();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return $response;
        }

        return $this->responseSucceed(message: 'Success');
    }

    public function GiveGift(GiveGiftRequest $request)
    {
        $transaction_type_id = TransactionType::where('name', EnumTransactionType::Gift)->pluck('id')->first();

        $friend = Friend::where('user_id', auth()->user()->id)->where('friend_id', $request->friend_id)->where('confirm_status', Status::CONFIRMED_FRIEND)->first();
        if ($friend) {
            DB::beginTransaction();
            try {
                $user = User::lockForUpdate()->where('id', auth()->user()->id)->first();
                $response = $this->endpoint->handle(
                    config('api.url.card'),
                    ServerPath::BUY_GIFT,
                    [
                        'user_id' => auth()->user()->id,
                        'user_model' => get_class(auth()->user()),
                        'friend_id' => $request->friend_id,
                        'friend_model' =>  get_class($friend),
                        'transaction_type_id' => $transaction_type_id,
                        'type' => $request->type,
                        'amount' => $request->amount,
                        'user_amount_before_transaction' => $user->amount,
                        'friend_amount' => $friend->amount,
                        'store_id' => $request->store_id,
                        'encrypt' => $request->encrypt

                    ]
                );

                if ($response['data']['message'] == 'Success') {
                    if (!($request->type == Status::STICKER)) {
                        User::where('id', auth()->user()->id)
                            ->update(['amount' => $user->amount - $request->amount]);
                    }
                } else {

                    throw new GeneralError();
                }

                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
                return $response;

                // throw new GeneralError();
            }
            return $this->responseSucceed(message: 'Success');
        } else {
            return $this->responseSomethingWentWrong(message: "Something went wrong");
        }
    }
}
