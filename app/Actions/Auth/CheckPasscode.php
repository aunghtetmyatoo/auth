<?php

namespace App\Actions\Auth;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use App\CustomFunctions\ResponseHelpers;

class CheckPasscode
{
    public function execute(string $passcode)
    {
        $user=auth()->user();

        if($user->frozen_at){
            return [
                'result' => 0,
                'message' => 'This account is temporarily locked. Please contact to call center (+959664153736)',
            ];
        }

        $password_mistake_count=$user->password_mistake_count;
        if(!password_verify($passcode,$user->password)){
            $password_mistake=$password_mistake_count+1 ;
            if($password_mistake < 3){
                $user->password_mistake_count=$password_mistake;
                $user->password_mistook_at=now();

            }elseif($password_mistake >=3){
                $user->frozen_at=now();
                $user->password_mistake_count = $password_mistake;
                $user->password_mistook_at = Carbon::now();
                $user->save();
                return [
                    'result' => 0,
                    'message' => 'This account is temporarily locked. Please contact to call center (+959664153736)',
                ];

            }
            $user->save();
            return [
                'result' => 0,
                'message' => 'Your Password is invalid',
            ];

        }
        return [
            'result' => 1,
            'message' => 'valid',
        ];
    }

}
