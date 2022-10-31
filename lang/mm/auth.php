<?php

use App\Constants\Gambling;

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed' => 'These credentials do not match our records.',
    'password' => 'The provided password is incorrect.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',
    'must_change_passcode' => 'You must change your passcode before continue.',
    'invalid' => [
        'device_id' => 'Your account has already Signed in other device, sign out now.',
        'session_id' => 'Your account has already Signed in other device, sign out now.',
        'user' => 'User does not exist.',
        'identity' => ':identity is incorrect.',
        'passcode' => 'Passcode is incorrect.',
        'password' => 'Password is incorrect.',
        'passport' => 'Passport is incorrect.',
    ],
    'account' => [
        'logged_in' => 'This account has already signed in on other device, please logout on that device and login again.',
        'locked' => 'This account is temporarily locked. Please contact to call center (' . Gambling::CALL_CENTER . ').',
        'will_lock' => [
            'identity' => 'Incorrect :identity. This account will be locked after next :identity mistake, Please contact to call center (' . Gambling::CALL_CENTER . ').',
            'passcode' => 'Incorrect passcode. This account will be locked after next passcode mistake, Please reset the passcode otherwise contact to call center (' . Gambling::CALL_CENTER . ').',
            'password' => 'Incorrect password. This account will be locked after next password mistake, Please reset the password otherwise contact to call center (' . Gambling::CALL_CENTER . ').',
        ],
    ],
];
