<?php

namespace App\Http\Controllers\ShanKoeMee;

use App\Actions\HandleEndpoint;
use App\Exceptions\GeneralError;
use App\Traits\Auth\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ShanKoeMee\Transfer\TransferToPlayRequest;
use App\Http\Requests\Api\ShanKoeMee\Transfer\TransferFromPlayRequest;

class TransferPlayController extends Controller
{
    use ApiResponse;

    public function __construct(private HandleEndpoint $handleEndpoint)
    {
    }

    public function transferToGame(TransferToPlayRequest $request)
    {
        ['game_type_id' => $game_type_id, 'amount' => $amount] = $request->all();

        if (!auth()->user()->id) {
            throw new GeneralError();
        }

        return $this->handleEndpoint->handle(server_name: "card_games", prefix: "transfers", route_name: "to_game", request: [
            'user_id' => auth()->user()->id,
            'game_type_id' => $game_type_id,
            'amount' => $amount,
        ]);
    }

    public function transferFromGame(TransferFromPlayRequest $request)
    {
        ['game_type_id' => $game_type_id, 'amount' => $amount] = $request->all();

        if (!auth()->user()->id) {
            throw new GeneralError();
        }

        return $this->handleEndpoint->handle(server_name: "card_games", prefix: "transfers", route_name: "from_game", request: [
            'user_id' => auth()->user()->id,
            'game_type_id' => $game_type_id,
            'amount' => $amount,
        ]);
    }
}
