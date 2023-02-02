<?php

return [
    'requested' => 'Recharge request already exists.',
    'cancelled' => 'Recharge cancelled successfully.',
    'confirmed' => 'This request is in processing, cannot cancel anymore.',
    'not_requested' => 'You currently do not have a recharge request.',
    'failed' => [
        'default' => 'Recharge request failed.',
        'max_daily' => 'Exceeded recharge daily limit amount for :channel.',
        'max_per_transaction' => 'Maximum recharge amount is :max_amount.',
        'min_per_transaction' => 'Minimum recharge amount is :min_amount.',
    ],
    'success' => [
        'requested' => [
            'title' => 'Recharge Requested',
            'message' => 'Your recharge request is requested.'
        ],
        'cancelled' => [
            'title' => 'Recharge Cancelled',
            'message' => 'Your recharge request cancelled successfully.'
        ],
    ]
];
