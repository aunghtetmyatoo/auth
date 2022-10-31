<?php

namespace App\Actions\Auth;

use App\Exceptions\OtpInvalidException;
use App\Interfaces\AdminUser;
use App\Interfaces\MobileUser;
use App\Models\Admin;
use App\Models\User;

class InvalidOtp
{
    public function handle(Admin|User $user = null, bool $is_backend_user)
    {
        if (!$user) {
            throw new OtpInvalidException();
        }
        $mistook_col = $is_backend_user ? 'bk_otp_mistook_at' : 'otp_mistook_at';
        $mistake_col = $is_backend_user ? 'bk_otp_mistake_count' : 'otp_mistake_count';

        // reset it if last otp mistook time is 1 day behind from now
        (new ResetOtpMistake())->handle(user: $user, mistake_col: $mistake_col, mistook_col: $mistook_col);

        $user->$mistook_col = now();
        $user->$mistake_col++;
        $user->save();

        throw new OtpInvalidException();
    }
}
