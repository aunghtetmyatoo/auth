<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Http\Request;

class SucceedLogin
{
    public function handle(User $user, Request $request): void
    {
        if ($user->device_id != $request->device_id) {
            $user->device_id  = $request->device_id;
            $user->noti_token = $request->noti_token;
            $user->language   = $request->language;
            $user->passcode_mistake_count = 0;
            $user->save();
            return;
        }
    }
}
