<?php

namespace App\Http\Controllers\ShanKoeMee;

use Illuminate\Http\Request;
use App\Actions\HandleEndpoint;
use App\Traits\Auth\ApiResponse;
use App\Http\Controllers\Controller;

class TransferPlayController extends Controller
{
    use ApiResponse;

    public function __construct(private HandleEndpoint $handleEndpoint)
    {
    }

    public function transferToGame(Request $request)
    {
        ['game_type_id' => $game_type_id, 'amount' => $amount] = $request->all();

        return $this->handleEndpoint->handle(server_name: "card_games", prefix: "transfers", route_name: "to_game", request: [
            'user_id' => auth()->user()->id,
            'game_type_id' => $game_type_id,
            'amount' => $amount,
        ]);
    }

    public function transferFromGame(Request $request)
    {
        ['game_type_id' => $game_type_id, 'coin' => $coin] = $request->all();

        return $this->handleEndpoint->handle(server_name: "card_games", prefix: "transfers", route_name: "from_game", request: [
            'user_id' => auth()->user()->id,
            'game_type_id' => $game_type_id,
            'coin' => $coin,
        ]);
    }
}
