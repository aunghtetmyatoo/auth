<?php

return [
    'real_time' => [
        'end_point' => "https://game-socket.com/api/v1",
        'friends' => [
            'prefix' => '/friends',
            'add' => '/add',
            'confirm' => '/confirm',
        ]
    ],

    'card_games' => [
        'end_point' => 'https://card.game-socket.com/api/v1/card-game',
        'tables' => [
            'prefix' => '/tables',
            'list' => '/list',
            'create' => '/create',
            'join' => '/join',
            'leave' => '/leave',
            'invite' => '/invite',
            'kick_out' => '/kick-out',
        ],
        'transfers' => [
            'prefix' => '/transfers',
            'to_game' => '/to-game',
            'from_game' => '/from-game',
        ],
        'ticket_moneys' => [
            'prefix' => '/ticket-money',
        ],
        'plays' => [
            'prefix' => '/plays',
            'direct' => '/direct',
        ],
        'matches' => [
            'prefix' => '/matches',
            'ready' => '/ready',
            'start' => '/start',
            'bet' => '/bet',
            'one_more_card' => '/one-more-card',
            'catch_three_card' => '/catch-three-card',
            'next_time_banker' => '/next-time-banker',
            'amount_change_request' => '/amount-change-request',
            'quit_match'=>'/quit-match',
            'cancel_quit_match'=>'/cancel-quit-match'
        ],
        'messages' => [
            'prefix' => '/messages',
            'public' => '/public',
            'private' => '/private',
        ],
    ]
];
