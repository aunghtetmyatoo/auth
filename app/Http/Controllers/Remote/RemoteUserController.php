<?php

namespace App\Http\Controllers\Remote;

use App\Models\User;
use App\Traits\Auth\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Remote\UpdatePlayStatusRequest;
use App\Http\Requests\Api\Remote\UpdateUserAmountRequest;

class RemoteUserController extends Controller
{
    use ApiResponse;

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
}
