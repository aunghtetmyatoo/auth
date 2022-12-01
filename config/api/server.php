<?php

return [
    'real_time' => [
        'end_point' => "http://localhost:3000/api/v1",
        'friends' => [
            'prefix' => '/friends',
            'list' => '/',
            'request_list' => '/request-list',
            'add' => '/add',
            'confirm' => '/confirm',
            'cancel' => '/cancel',
            'unfriend' => '/unfriend',
        ]
    ],

    'card_games' => [
        'end_point' => 'http://localhost:8002/api/v1/card-game',
        'tables' => [
            'prefix' => '/tables',
            'list' => '/list',
            'create' => '/create',
            'join' => '/join',
            'leave' => '/leave',
            'invite' => '/invite',
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
        ],
        'matches' => [
            'prefix' => '/matches',
            'start' => '/start',
            'bet' => '/bet',
            'share_card' => '/share-card',
            'one_more_card' => '/one-more-card',
            'win_or_lose' => '/win-or-lose',
        ],
    ]
];
