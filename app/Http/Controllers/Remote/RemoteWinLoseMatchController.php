<?php

namespace App\Http\Controllers\Remote;

use App\Constants\Status;
use App\Models\WinLoseMatch;
use App\Traits\Auth\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Remote\WinLoseMatch\UpdateBankerBetCoinRequest;
use App\Http\Requests\Api\Remote\WinLoseMatch\UpdateBankerWinLoseCoinRequest;
use App\Http\Requests\Api\Remote\WinLoseMatch\UpdateBankerWinLoseMatchRequest;
use App\Http\Requests\Api\Remote\WinLoseMatch\UpdateBettorRequest;

class RemoteWinLoseMatchController extends Controller
{
    use ApiResponse;

    public function updateBettor(UpdateBettorRequest $request)
    {
        ['user_id' => $user_id, 'game_type_id' => $game_type_id, 'win_lose_status' => $win_lose_status, 'bet_coin' => $bet_coin, 'win_lose_coin' => $win_lose_coin, 'privacy' => $privacy] = $request->all();

        $user_log = WinLoseMatch::where('user_id', $user_id)->where('game_type_id', $game_type_id)->where('privacy', $privacy)->first();

        if (!$user_log) {
            $user_log = $this->create(user_id: $user_id, game_type_id: $game_type_id, privacy: $privacy);
        }

        ['win_match' => $win_match, 'loss_match' => $loss_match, 'win_coin' => $win_coin, 'loss_coin' => $loss_coin, 'total_match' => $total_match, 'bet_coin' => $user_bet_coin, 'win_streak' => $win_streak] = $user_log;

        $user_log->update([
            'win_match' => $win_lose_status === Status::WIN ? ++$win_match : $win_match,
            'loss_match' => $win_lose_status === Status::LOSE ? ++$loss_match : $loss_match,
            'total_match' => ++$total_match,
            'bet_coin' => $user_bet_coin + $bet_coin,
            'win_coin' => $win_lose_status === Status::WIN ? ($win_coin + $win_lose_coin) : $win_coin,
            'loss_coin' => $win_lose_status === Status::LOSE ? ($loss_coin + $win_lose_coin) : $loss_coin,
            'win_streak' => $win_lose_status === Status::WIN ? ++$win_streak : 0,
            'win_rate' => $win_match / $total_match,
        ]);

        return $this->responseSucceed(
            message: "Successfully updated!."
        );
    }

    public function updateBankerBetCoin(UpdateBankerBetCoinRequest $request)
    {
        ['user_id' => $user_id, 'game_type_id' => $game_type_id, 'bet_coin' => $bet_coin, 'privacy' => $privacy] = $request->all();

        $user_log = WinLoseMatch::where('user_id', $user_id)->where('game_type_id', $game_type_id)->where('privacy', $privacy)->first();

        if (!$user_log) {
            $user_log = $this->create(user_id: $user_id, game_type_id: $game_type_id, privacy: $privacy);
        }

        $user_log->update([
            'bet_coin' => $user_log->bet_coin + $bet_coin,
        ]);

        return $this->responseSucceed(
            message: "Successfully updated!."
        );
    }

    public function updateBankerWinLoseCoin(UpdateBankerWinLoseCoinRequest $request)
    {
        ['user_id' => $user_id, 'game_type_id' => $game_type_id, 'win_lose_coin' => $win_lose_coin, 'win_lose_status' => $win_lose_status, 'privacy' => $privacy] = $request->all();

        $user_log = WinLoseMatch::where('user_id', $user_id)->where('game_type_id', $game_type_id)->where('privacy', $privacy)->first();

        if (!$user_log) {
            $user_log = $this->create(user_id: $user_id, game_type_id: $game_type_id, privacy: $privacy);
        }

        ['win_coin' => $win_coin, 'loss_coin' => $loss_coin] = $user_log;

        $user_log->update([
            'win_coin' => $win_lose_status === Status::WIN ? ($win_coin + $win_lose_coin) : $win_coin,
            'loss_coin' => $win_lose_status === Status::LOSE ? ($loss_coin + $win_lose_coin) : $loss_coin,
        ]);

        return $this->responseSucceed(
            message: "Successfully updated!."
        );
    }

    public function updateBankerWinLoseMatch(UpdateBankerWinLoseMatchRequest $request)
    {
        ['user_id' => $user_id, 'game_type_id' => $game_type_id, 'win_lose_status' => $win_lose_status, 'privacy' => $privacy] = $request->all();

        $user_log = WinLoseMatch::where('user_id', $user_id)->where('game_type_id', $game_type_id)->where('privacy', $privacy)->first();

        if (!$user_log) {
            $user_log = $this->create(user_id: $user_id, game_type_id: $game_type_id, privacy: $privacy);
        }

        ['win_match' => $win_match, 'loss_match' => $loss_match, 'total_match' => $total_match, 'win_streak' => $win_streak] = $user_log;

        $user_log->update([
            'win_match' => $win_lose_status === Status::WIN ? ++$win_match : $win_match,
            'loss_match' => $win_lose_status === Status::LOSE ? ++$loss_match : $loss_match,
            'total_match' => ++$total_match,
            'win_streak' => $win_lose_status === Status::WIN ? ++$win_streak : 0,
        ]);

        return $this->responseSucceed(
            message: "Successfully updated!."
        );
    }

    public function create($user_id, $game_type_id, $privacy)
    {
        $user_log = WinLoseMatch::create([
            'user_id' => $user_id,
            'game_type_id' => $game_type_id,
            'privacy' => $privacy,
            'total_match' => 0,
            'win_match' => 0,
            'loss_match' => 0,
            'bet_coin' => 0,
            'win_coin' => 0,
            'loss_coin' => 0,
            'win_streak' => 0,
        ]);

        return $user_log;
    }
}
