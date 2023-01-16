<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\GameType;
use App\Traits\Auth\ApiResponse;
use App\Http\Requests\Api\Remote\UpdateGameCoinRequest;
use App\Http\Requests\Api\Remote\UpdatePlayStatusRequest;
use App\Http\Requests\Api\Remote\UpdateUserAmountRequest;
use App\Http\Requests\Api\Remote\CreateGameTypeUserRequest;
use App\Models\Admin;
use App\Models\History;
use App\Models\TransactionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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

    public function addHistory(Request $request)
    {
        $history = new History;
        $history->transaction_type_id = $request->transaction_type_id;
        $history->historiable_id = $request->historiable_id;
        $history->historiable_type =  $request->historiable_type;
        $history->transactionable_id = $request->user_id;
        $history->transactionable_type = $request->user_model;
        $history->reference_id =  strtoupper(Str ::random(15));
        $history->amount_before_transaction = $request->user_amount_before_transaction;
        $history->amount_after_transaction = $request->user_amount_after_transaction;
        $history->is_from = $request->is_from;
        $history->save();
    }
}
