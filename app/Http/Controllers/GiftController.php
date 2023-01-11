<?php

namespace App\Http\Controllers;

use App\Actions\HandleEndpoint;
use App\Traits\Auth\ApiResponse;
use Illuminate\Http\Request;

class GiftController extends Controller
{
    use ApiResponse;

    public function __construct(private HandleEndpoint $handleEndpoint)
    {
    }

    public function buyGift(Request $request){

        if($request->friend_id)
        {
            return $this->handleEndpoint->handle(server_name: "card_games", prefix: "gift", route_name: "buy_gift",
            request: [
                'user_id' => auth()->user()->id,
                'friend_id' => $request->friend_id,
                'type' => $request->type,
                'amount' => $request->amount,
                'store_id' => $request->store_id,
            ]);
        }else{
            return $this->handleEndpoint->handle(server_name: "card_games", prefix: "gift", route_name: "buy_gift",
            request: [
                'user_id' => auth()->user()->id,
                'type' => $request->type,
                'amount' => $request->amount,
                'store_id' => $request->store_id,
            ]);
        }
    }
}
