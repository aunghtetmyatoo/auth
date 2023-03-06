<?php

namespace App\Constants;

class ServerPath
{
    // const CARD_GAME = 'http://card-games.test/api/v1/card-game/';
    const CARD_GAME = 'https://card.game-socket.com/';
    // const GAMBLING_AUTH = 'http://gamblingauth.test/api/v1/';
    const GAMBLING_AUTH = 'https://auth.game-socket.com/';
    const GAME_SOCKET = 'https://game-socket.com:8081/api/v1/';

    // For Game Socket
    // For Game Socket, Friend API
    const FRIEND =  self::GAME_SOCKET . 'friends/';
    const ADD_FRIEND = self::FRIEND . 'add';
    const CONFIRM_FRIEND = self::FRIEND . 'confirm';

    // For Game Socket , Admin Dashboard API
    const ADMIN_DASHBOARD =  self::GAME_SOCKET . 'admin-dashboard/';
    const GET_RECHARGE_REQUEST = self::ADMIN_DASHBOARD . 'get-recharge-request';
    const GET_WITHDRAW_REQUEST = self::ADMIN_DASHBOARD . 'get-withdraw-request';

    // For Card Game, Message API
    const MESSAGES = self::GAME_SOCKET . 'messages/';
    const PUBLIC_MESSAGE = self::MESSAGES . 'public';
    const PRIVATE_MESSAGE = self::MESSAGES . 'private';

    // For Card Game
    // For Card Game, Match API
    const MATCHES = self::CARD_GAME . 'matches/';
    const READY = self::MATCHES . 'ready';
    const START_MATCH = self::MATCHES . 'start';
    const BET = self::MATCHES . 'bet';
    const ONE_MORE_CARD = self::MATCHES . 'one-more-card';
    const CATCH_THREE_CARD = self::MATCHES . 'catch-three-card';
    const NEXT_TIME_BANKER = self::MATCHES . 'next-time-banker';
    const AMOUNT_CHANGE_REQUEST = self::MATCHES . 'amount-change-request';
    const QUIT_MATCH = self::MATCHES . 'quit-match';
    const CANCEL_QUIT_MATCH = self::MATCHES . 'cancel-quit-match';

    // For Card Game, Table API
    const TABLES = self::CARD_GAME . 'tables/';
    const TABLES_LIST =  self::TABLES . 'list';
    const CREATE_TABLE = self::TABLES . 'create';
    const JOIN_TABLE =  self::TABLES . 'join';
    const LEAVE_TABLE = self::TABLES . 'leave';
    const INVITE_FRIEND =  self::TABLES . 'invite';
    const KICK_OUT = self::TABLES . 'kick-out';

    // For Card Game, Ticket Money API
    const TICKET_MONEY = self::CARD_GAME . 'ticket-money';

    // For Card Game, PLAY API
    const PLAY = self::CARD_GAME . 'plays/';
    const PLAY_DIRECT = self::PLAY . 'direct';
    const PLAY_WITH_BOT = self::PLAY . 'play-with-bots';

    // For Card Game, Gift API
    const GIFT =  self::CARD_GAME . 'gift/';
    const BUY_GIFT = self::GIFT . 'buy-gift';

    // For Card Game, Gift API
    const ROOM_TYPE =  self::CARD_GAME . 'room-type/';
    const ROOM_TYPE_LIST = self::ROOM_TYPE . 'list';
}
