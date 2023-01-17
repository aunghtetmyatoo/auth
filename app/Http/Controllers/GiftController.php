<?php

namespace App\Http\Controllers;

use App\Actions\HandleEndpoint;
use App\Constants\Status;
use App\Constants\TransactionTypeConstant;
use App\Exceptions\GeneralError;
use App\Http\Requests\Api\GiftRequest\BuyGiftRequest;
use App\Http\Requests\Api\GiftRequest\GiveGiftRequest;
use App\Models\History;
use App\Models\TransactionType;
use App\Models\User;
use App\Traits\Auth\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
class GiftController extends Controller
{
    use ApiResponse;

    public function __construct(private HandleEndpoint $handleEndpoint)
    {
    }

    public function buyGift(BuyGiftRequest $request){

        $transaction_type_id = TransactionType::where('name',TransactionTypeConstant::Gift_Transaction)->pluck('id')->first();

            DB::beginTransaction();
            try {
                $user = User::lockForUpdate()->where('id', auth()->user()->id)->first();
                $response = $this->handleEndpoint->handle(  server_name: "card_games",
                                                            prefix: "gift",
                                                            route_name: "buy_gift",
                                                            request: [  'user_id' => auth()->user()->id,
                                                                        'user_model' => get_class(auth()->user()),
                                                                        'transaction_type_id' => $transaction_type_id,
                                                                        'type' => $request->type,
                                                                        'amount' => $request->amount,
                                                                        'user_amount_before_transaction' => $user->amount,
                                                                        'store_id' => $request->store_id, ]);

                if($response)
                {
                    if (!($request->type == Status::STICKER)) {
                        User::where('id', auth()->user()->id)
                        ->update(['amount' => $user->amount - $request->amount]);}
                }
                else{
                    throw new GeneralError();
                }

            DB::commit();
            } catch (Exception $e) {
                DB::rollback();
                throw new GeneralError();
            }

            return $this->responseSucceed(message: 'Success');
    }


    public function GiveGift(GiveGiftRequest $request){

        $transaction_type_id = TransactionType::where('name',TransactionTypeConstant::Gift_Transaction)->pluck('id')->first();

        $friend = User::find($request->friend_id);

        if($friend)
        {
            DB::beginTransaction();
            try {
                $user = User::lockForUpdate()->where('id', auth()->user()->id)->first();
                $response = $this->handleEndpoint->handle(  server_name: "card_games",
                                                            prefix: "gift",
                                                            route_name: "buy_gift",
                                                            request: [  'user_id' => auth()->user()->id,
                                                                        'user_model' => get_class(auth()->user()),
                                                                        'friend_id' => $request->friend_id,
                                                                        'friend_model' =>  get_class($friend),
                                                                        'transaction_type_id' => $transaction_type_id,
                                                                        'type' => $request->type,
                                                                        'amount' => $request->amount,
                                                                        'user_amount_before_transaction' => $user->amount,
                                                                        'friend_amount' => $friend->amount,
                                                                        'store_id' => $request->store_id, ]);

                if($response)
                {
                    if (!($request->type == Status::STICKER)) {
                        User::where('id', auth()->user()->id)
                        ->update(['amount' => $user->amount - $request->amount]);}
                }
                else{
                    throw new GeneralError();
                }

            DB::commit();
            } catch (Exception $e) {
                DB::rollback();
                throw new GeneralError();
            }

            return $this->responseSucceed(message: 'Success');
        }
        else{
            return $this->responseSomethingWentWrong(message: "Something went wrong");
        }
    }
}


