<?php

return [

    'failed' => [
        'default' => 'Transaction failed.',
        'max_daily' => 'Exceeded Withdraw daily limit amount for :channel.',
        'max_per_transaction' => 'Maximum Withdraw amount is :max_amount.',
        'min_per_transaction' => 'Minimum Withdraw amount is :min_amount.',
    ],
    'success' => [
        'requested' => [
            'title' => 'Withdraw Requested',
            'message' => 'Your Withdraw request is requested.'
        ],
        'cancelled' => [
            'title' => 'Recharge Cancelled',
            'message' => 'Your recharge request cancelled successfully.'
        ],
    ]
];
