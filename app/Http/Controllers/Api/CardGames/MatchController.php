<?php

namespace App\Http\Controllers\Api\CardGames;

use App\Actions\Endpoint;
use App\Constants\ServerPath;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function __construct(private Endpoint $endpoint)
    {
    }

    public function reconnect(Request $request)
    {
        return $this->endpoint->handle(config('api.url.card'), ServerPath::RECONNECT, [
            'room_id' => $request->room_id,
            'game_match_id' => $request->game_match_id,
        ]);
    }

    public function matchStart(Request $request)
    {
        return $this->endpoint->handle(config('api.url.card'), ServerPath::START_MATCH, [
            'room_id' => $request->room_id,
            'game_type_id' => $request->game_type_id,
            'creator_id' => auth()->user()->id,
        ]);
    }

    public function betAmount(Request $request)
    {
        return $this->endpoint->handle(config('api.url.card'), ServerPath::BET, [
            'user_id' => auth()->user()->id,
            'game_match_id' => $request->game_match_id,
            'bet_amount' => $request->bet_amount,
        ]);
    }

    public function oneMoreCard(Request $request)
    {
        return $this->endpoint->handle(config('api.url.card'), ServerPath::ONE_MORE_CARD, [
            'user_id' => auth()->user()->id,
            'one_more_card' => $request->one_more_card,
            'game_match_id' => $request->game_match_id,
        ]);
    }

    public function catchThreeCard(Request $request)
    {
        return $this->endpoint->handle(config('api.url.card'), ServerPath::CATCH_THREE_CARD, [
            'catch_three_card' => $request->catch_three_card,
            'game_match_id' => $request->game_match_id,
            'user_id' => auth()->user()->id,
        ]);
    }

    public function nextTimeBanker(Request $request)
    {
        return $this->endpoint->handle(config('api.url.card'), ServerPath::NEXT_TIME_BANKER, [
            'next_time_banker' => $request->next_time_banker,
            'game_match_id' => $request->game_match_id,
            'user_id' => auth()->user()->id,
        ]);
    }

    public function amountChangeRequest(Request $request)
    {
        return $this->endpoint->handle(config('api.url.card'), ServerPath::AMOUNT_CHANGE_REQUEST, [
            'amount_change_request' => $request->amount_change_request,
            'game_match_id' => $request->game_match_id,
            'user_id' => auth()->user()->id,
        ]);
    }

    public function quitMatch(Request $request)
    {
        return $this->endpoint->handle(config('api.url.card'), ServerPath::QUIT_MATCH, [
            'game_match_id' => $request->game_match_id,
            'user_id' => auth()->user()->id,
        ]);
    }

    public function cancelQuitMatch(Request $request)
    {
        return $this->endpoint->handle(config('api.url.card'), ServerPath::CANCEL_QUIT_MATCH, [
            'game_match_id' => $request->game_match_id,
            'user_id' => auth()->user()->id,
        ]);
    }
}
