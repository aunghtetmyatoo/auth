<?php

namespace App\Constants;

class ServerPath
{
    const AUTH_PREFIX = '/api/v1/';
    const CARD_PREFIX = '/api/v1/card-game/';
    const SOCKET_PREFIX = '/api/v1/';


    /** Start Game Socket */
    // Friend
    const FRIEND =  self::SOCKET_PREFIX . 'friends/';
    const ADD_FRIEND = self::FRIEND . 'add';
    const CONFIRM_FRIEND = self::FRIEND . 'confirm';

    // Recharge, Withdraw
    const ADMIN_DASHBOARD =  self::SOCKET_PREFIX . 'admin-dashboard/';
    const NOTI_FOR_MONITOR_LOG_REQUEST = self::ADMIN_DASHBOARD . 'noti-for-monitor-log-request';
    const GET_RECHARGE_REQUEST = self::ADMIN_DASHBOARD . 'get-recharge-request';
    const GET_WITHDRAW_REQUEST = self::ADMIN_DASHBOARD . 'get-withdraw-request';
    /** End Game Socket */


    /** Start Card Game */
    // Match
    const MATCHES = self::CARD_PREFIX . 'matches/';
    const READY = self::MATCHES . 'ready';
    const START_MATCH = self::MATCHES . 'start';
    const BET = self::MATCHES . 'bet';
    const ONE_MORE_CARD = self::MATCHES . 'one-more-card';
    const CATCH_THREE_CARD = self::MATCHES . 'catch-three-card';
    const NEXT_TIME_BANKER = self::MATCHES . 'next-time-banker';
    const AMOUNT_CHANGE_REQUEST = self::MATCHES . 'amount-change-request';
    const QUIT_MATCH = self::MATCHES . 'quit-match';
    const CANCEL_QUIT_MATCH = self::MATCHES . 'cancel-quit-match';

    // Table
    const TABLES = self::CARD_PREFIX . 'tables/';
    const TABLES_LIST =  self::TABLES . 'list';
    const CREATE_TABLE = self::TABLES . 'create';
    const JOIN_TABLE =  self::TABLES . 'join';
    const JOIN_TABLE_SIDE_BETTOR =  self::TABLES . 'join/side-bettor';
    const LEAVE_TABLE = self::TABLES . 'leave';
    const INVITE_FRIEND =  self::TABLES . 'invite';
    const KICK_OUT = self::TABLES . 'kick-out';

    // Ticket Money
    const TICKET_MONEY = self::CARD_PREFIX . 'ticket-money';

    // Play
    const PLAY = self::CARD_PREFIX . 'plays/';
    const PLAY_DIRECT = self::PLAY . 'direct';
    const PLAY_WITH_BOT = self::PLAY . 'play-with-bots';

    // Gift
    const GIFT =  self::CARD_PREFIX . 'gift/';
    const BUY_GIFT = self::GIFT . 'buy-gift';

    // Room Type
    const ROOM_TYPE =  self::CARD_PREFIX . 'room-type/';
    const ROOM_TYPE_LIST = self::ROOM_TYPE . 'list';

    // Message
    const MESSAGE =  self::CARD_PREFIX . 'messages/';
    const PUBLIC = self::MESSAGE . 'public';
    const PRIVATE = self::MESSAGE . 'private';
    /** End Card Game */
}
