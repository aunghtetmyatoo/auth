<?php

namespace App\Http\Controllers\Match;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MatchController extends Controller
{
    public function matchStart(Request $request)
    {
        $response = Http::post(config('api.server.card_games.end_point') . config('api.server.card_games.matches.prefix') . config('api.server.card_games.matches.start'), [
            'room_id' => $request->room_id,
            'game_type_id' => $request->game_type_id,
        ]);

        return json_decode($response, true);
    }

    public function betAmount(Request $request)
    {
        $response = Http::post(config('api.server.card_games.end_point') . config('api.server.card_games.matches.prefix') . config('api.server.card_games.matches.bet'), [
            'user_id' => auth()->user()->id,
            'room_id' => $request->room_id,
            'game_match_id' => $request->game_match_id,
            'bet_amount' => $request->bet_amount,
        ]);

        return json_decode($response, true);
    }

    public function shareCard(Request $request)
    {
        $response = Http::post(config('api.server.card_games.end_point') . config('api.server.card_games.matches.prefix') . config('api.server.card_games.matches.share_card'), [
            'user_id' => auth()->user()->id,
            'game_match_id' => $request->game_match_id,
        ]);

        return json_decode($response, true);
    }

    public function oneMoreCard(Request $request)
    {
        $response = Http::post(config('api.server.card_games.end_point') . config('api.server.card_games.matches.prefix') . config('api.server.card_games.matches.one_more_card'), [
            'user_id' => auth()->user()->id,
            'one_more_card' => $request->one_more_card,
            'game_match_id' => $request->game_match_id,
        ]);

        return json_decode($response, true);
    }

    public function winOrLose(Request $request)
    {
        $response = Http::post(config('api.server.card_games.end_point') . config('api.server.card_games.matches.prefix') . config('api.server.card_games.matches.win_or_lose'), [
            'game_match_id' => $request->game_match_id,
        ]);

        return json_decode($response, true);
    }
}
