<?php

namespace App\Actions\Auth;

use App\Models\User;
use App\Models\Admin;
use App\Exceptions\PasswordInvalidException;

class InvalidPassword
{
    public function handle(Admin|User $user = null, bool $is_backend_user)
    {
        if (!$user) {
            throw new PasswordInvalidException();
        }
        $mistook_col = $is_backend_user ? 'bk_password_mistook_at' : 'password_mistook_at';
        $mistake_col = $is_backend_user ? 'bk_password_mistake_count' : 'password_mistook_count';

        if ($user->mistake_col > config("password.player.limit")) {
            $user->$mistook_col = now();
        }
        $user->$mistake_col++;
        $user->save();

        throw new PasswordInvalidException();
    }
}
