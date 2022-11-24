<?php

namespace App\Http\Controllers\ShanKoeMee;

use App\Exceptions\GeneralError;
use App\Traits\Auth\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\Api\ShanKoeMee\Transfer\TransferToPlayRequest;
use App\Http\Requests\Api\ShanKoeMee\Transfer\TransferFromPlayRequest;

class TransferPlayController extends Controller
{
    use ApiResponse;

    public function transferToGame(TransferToPlayRequest $request)
    {
        ['game_type_id' => $game_type_id, 'amount' => $amount] = $request->all();

        if (!auth()->user()->id) {
            throw new GeneralError();
        }

        $response = Http::post(config('api.server.card_games.end_point') . config('api.server.card_games.transfers.prefix') . config('api.server.card_games.transfers.to_game'), [
            'user_id' => auth()->user()->id,
            'game_type_id' => $game_type_id,
            'amount' => $amount,
        ]);

        return json_decode($response, true);
    }

    public function transferFromGame(TransferFromPlayRequest $request)
    {
        ['game_type_id' => $game_type_id, 'amount' => $amount] = $request->all();

        if (!auth()->user()->id) {
            throw new GeneralError();
        }

        $response = Http::post(config('api.server.card_games.end_point') . config('api.server.card_games.transfers.prefix') . config('api.server.card_games.transfers.from_game'), [
            'user_id' => auth()->user()->id,
            'game_type_id' => $game_type_id,
            'amount' => $amount,
        ]);

        return json_decode($response, true);
    }
}
