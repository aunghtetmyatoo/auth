<?php

use App\Constants\Gambling;

return [
    'over_limit' => [
        'request' => 'You can not request OTP more than ' . config('auth.index.allow.requests.otp') . ' times a day.',
        'verify' => 'You can not verify OTP more than ' . config('auth.index.allow.mistake.otp') . ' times a day.',
    ],
    'expired' => 'OTP is expired.',
    'invalid' => 'OTP is invalid.',
    'sent' => 'OTP is sent successfully.',
    'verified' => 'OTP is verified.',
    'failed' => 'Failed to send OTP',
    'blocked' => 'Your device has been blocked to request OTP, please contact to call center (' . Gambling::CALL_CENTER . ').',
    'message' => " is your Nine Pay OTP. Please don't share it to anyone.",
];
