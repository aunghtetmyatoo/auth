<?php

return [
    'allow' => [
        'requests' => [
            'otp' => 300,
        ],
        'mistake' => [
            'otp' => 300,
            'identity' => 3,
            'mfa' => 3,
            'passcode' => 3,
            'password' => 10,
        ],
    ],
];
