<?php

namespace App\Http\Controllers\Remote;

use App\Models\GameType;
use App\Traits\Auth\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Remote\UpdateGameCoinRequest;
use App\Http\Requests\Api\Remote\CreateGameTypeUserRequest;

class RemoteGameTypeUserController extends Controller
{
    use ApiResponse;

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
}
