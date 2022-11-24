<?php

namespace App\Http\Controllers\Play;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PlayController extends Controller
{
    public function playDirect(Request $request)
    {
        $response = Http::post(config('api.server.card_games.end_point') . config('api.server.card_games.plays.prefix'), [
            'user_id' => auth()->user()->id,
            'game_type_id' => $request->to_user_id,
        ]);
        return json_decode($response, true);
    }
}
