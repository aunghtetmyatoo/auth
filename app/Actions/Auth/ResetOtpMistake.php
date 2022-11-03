<?php

namespace App\Actions\Auth;

use App\Models\User;
use Carbon\Carbon;
use App\Models\Admin;

class ResetOtpMistake
{
    public function handle(Admin|User $user, string $mistake_col, string $mistook_col)
    {
        // reset it if last otp mistook time is 1 day behind from now
        if ($user->$mistook_col && Carbon::parse($user->$mistook_col)->diffInDays(now()) === 1) {
            $user->$mistake_col = 0;
            $user->save();
        }
    }
}
