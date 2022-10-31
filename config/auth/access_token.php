<?php

return [
    'min' => 120,
    'max' => 220,
    'expires' => [

        // For Mobile
        // register
        'mb_register_verify_otp' => 15,
        'mb_register' => 300,

        // login
        'mb_login_verify_otp' => 15,
        'mb_login_verify_identity' => 15,

        //forgot passcode
        'mb_forgot_passcode_update' => 15,

    ]
];
