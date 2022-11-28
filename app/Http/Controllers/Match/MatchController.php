<?php

namespace App\Http\Controllers\Match;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MatchController extends Controller
{
    public function betAmount(Request $request)
    {

        $response = Http::post(config('api.server.card_games.end_point') . config('api.server.card_games.matches.prefix') . config('api.server.card_games.matches.bet_amount'), [
            'user_id' => auth()->user()->id,
            'room_id' => $request->room_id,
            'game_type_id' => $request->game_type_id,
            'game_match_id' => $request->game_match_id,
            'bet_amount' => $request->bet_amount,
        ]);
        return json_decode($response, true);
    }
}
