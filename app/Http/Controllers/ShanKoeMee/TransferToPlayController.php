<?php

namespace App\Http\Controllers\ShanKoeMee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ShanKoeMee\Transaction\TransferToGame;
use App\Models\User;
use App\Traits\Auth\ApiResponse;
use Illuminate\Support\Facades\Http;

class TransferToPlayController extends Controller
{
    use ApiResponse;

    public function transferToGame(TransferToGame $request)
    {
        $game_type_id = $request->game_type_id;
        $amount = $request->amount;
        $response = Http::post(config("api.server.game.end_point") . config("api.server.card.prefix") . config("api.server.card.transfer"), [
            'user_id' => auth()->user()->id,
            'game_type_id' => $game_type_id,
            'amount' => $amount,
        ]);
        return $response->body();
    }
}
