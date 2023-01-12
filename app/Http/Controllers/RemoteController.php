<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\GameType;
use App\Traits\Auth\ApiResponse;
use App\Http\Requests\Api\Remote\UpdateGameCoinRequest;
use App\Http\Requests\Api\Remote\UpdatePlayStatusRequest;
use App\Http\Requests\Api\Remote\UpdateUserAmountRequest;
use App\Http\Requests\Api\Remote\CreateGameTypeUserRequest;

class RemoteController extends Controller
{
    use ApiResponse;

    public function __construct()
    {
    }

    /** User Model Start */
    public function updatePlayStatus(UpdatePlayStatusRequest $request)
    {
        ['user_id' => $user_id, 'status' => $status] = $request->all();

        User::find($user_id)->update([
            'play' => $status
        ]);

        return $this->responseSucceed(
            message: "Successfully updated user's play status!."
        );
    }

    public function updateUserAmount(UpdateUserAmountRequest $request)
    {
        ['user_id' => $user_id, 'amount' => $amount] = $request->all();

        User::find($user_id)->update([
            'amount' => $amount
        ]);

        return $this->responseSucceed(
            message: "Successfully updated user's amount!."
        );
    }
    /** User Model End */

    /** GameTypeUser Model Start */
    public function createGameTypeUser(CreateGameTypeUserRequest $request)
    {
        ['user_id' => $user_id, 'game_type_id' => $game_type_id, 'coin' => $coin] = $request->all();

        GameType::find($game_type_id)->users()->attach($user_id, [
            'coin' => $coin
        ]);

        return $this->responseSucceed(
            message: "Successfully created game type user!."
        );
    }

    public function updateGameCoin(UpdateGameCoinRequest $request)
    {
        ['user_id' => $user_id, 'game_type_id' => $game_type_id, 'coin' => $coin] = $request->all();

        GameType::find($game_type_id)->users()->updateExistingPivot($user_id, [
            'coin' => $coin
        ]);

        return $this->responseSucceed(
            message: "Successfully updated user's game coin!."
        );
    }
    /** GameTypeUser Model Start */
}
