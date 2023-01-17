<?php

namespace App\Http\Controllers\Remote;

use App\Models\WinLossMatch;
use Illuminate\Http\Request;
use App\Traits\Auth\ApiResponse;
use App\Http\Controllers\Controller;

class RemoteWinLoseLogController extends Controller
{
    use ApiResponse;

    public function updateUserMatchHistory(Request $request)
    {

        $winLossMatch = WinLossMatch::where('user_id', $request->user_id)->where('game_type_id', $request->game_type_id)->where('privacy', $request->privacy)->first();

        if ($winLossMatch) {
            if ($request->new_match) {
                $winLossMatch->total_match++;
            }

            if ($request->win_the_match) {
                $winLossMatch->win_match++;
            }

            if ($request->loss_the_match) {
                $winLossMatch->loss_match++;
            }

            $winLossMatch->bet_coin = $winLossMatch->bet_coin + $request->bet_coin;
            $winLossMatch->win_coin = $winLossMatch->win_coin + $request->win_coin;
            $winLossMatch->loss_coin = $winLossMatch->loss_coin + $request->loss_coin;
            $winLossMatch->save();
        } else {

            $winLossMatch = new WinLossMatch();

            $winLossMatch->user_id = $request->user_id;
            $winLossMatch->game_type_id = $request->game_type_id;
            $winLossMatch->privacy =  $request->privacy;

            if ($request->new_match) {
                $winLossMatch->total_match++;
            }

            if ($request->win_the_match) {
                $winLossMatch->win_match++;
            }

            if ($request->loss_the_match) {
                $winLossMatch->loss_match++;
            }

            $winLossMatch->bet_coin = $winLossMatch->bet_coin + $request->bet_coin;
            $winLossMatch->win_coin = $winLossMatch->win_coin + $request->win_coin;
            $winLossMatch->loss_coin = $winLossMatch->loss_coin + $request->loss_coin;
            $winLossMatch->save();
        }
    }
}
