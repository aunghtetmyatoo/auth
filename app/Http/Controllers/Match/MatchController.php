<?php

namespace App\Http\Controllers\Match;

use App\Actions\HandleEndpoint;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function __construct(private HandleEndpoint $handleEndpoint)
    {
    }

    public function matchStart(Request $request)
    {
        return $this->handleEndpoint->handle(server_name: "card_games", prefix: "matches", route_name: "start", request: [
            'room_id' => $request->room_id,
            'game_type_id' => $request->game_type_id,
            'creator' => auth()->user(),
        ]);
    }

    public function betAmount(Request $request)
    {
        return $this->handleEndpoint->handle(server_name: "card_games", prefix: "matches", route_name: "bet", request: [
            'user_id' => auth()->user()->id,
            'room_id' => $request->room_id,
            'game_match_id' => $request->game_match_id,
            'bet_amount' => $request->bet_amount,
        ]);
    }

    public function oneMoreCard(Request $request)
    {
        return $this->handleEndpoint->handle(server_name: "card_games", prefix: "matches", route_name: "one_more_card", request: [
            'user_id' => auth()->user()->id,
            'one_more_card' => $request->one_more_card,
            'game_match_id' => $request->game_match_id,
        ]);
    }
}
