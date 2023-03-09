<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GiftController;
use App\Http\Controllers\Api\FriendController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\TransferPlayController;
use App\Http\Controllers\Api\CardGames\PlayController;
use App\Http\Controllers\Api\CardGames\MatchController;
use App\Http\Controllers\Api\CardGames\TableController;
use App\Http\Controllers\Api\CardGames\RoomTypeController;
use App\Http\Controllers\Api\Payment\TransactionController;
use App\Http\Controllers\Api\CardGames\TicketMoneyController;
use App\Http\Controllers\Api\Payment\CashOutRequestController;

Route::prefix('/transactions')->controller(TransactionController::class)->group(function () {
    Route::post('/deposit', 'deposit');
    Route::post('/withdraw', 'withdraw');
});

Route::prefix('/friends')->controller(FriendController::class)->group(function () {
    Route::post('/', 'index');
    Route::post('/request-list', 'requestList');
    Route::post('/add', 'addFriend')->middleware('check_relationship');
    Route::post('/confirm', 'confirmFriend');
    Route::post('/cancel', 'cancelFriend');
    Route::post('/unfriend', 'unfriend');
});

Route::middleware('check_coin_amount')->prefix('/transfers')->controller(TransferPlayController::class)->group(function () {
    Route::post('to-game', 'transferToGame');
    Route::post('from-game', 'transferFromGame');
});

Route::prefix('/ticket-money')->controller(TicketMoneyController::class)->group(function () {
    Route::post('/', 'index');
});

Route::prefix('/tables')->controller(TableController::class)->group(function () {
    Route::post('/list', 'listPublicTable');
    Route::post('/create', 'create');
    Route::post('/join', 'join');
    Route::post('/join/side-bettor', 'joinBySideBettor');
    Route::post('/leave', 'leave');
    Route::post('/invite', 'inviteFriend');
    Route::post('/kick-out', 'kickOut');
});

Route::prefix('/plays')->controller(PlayController::class)->group(function () {
    Route::post('/direct', 'playDirect');
    Route::post('/play-with-bots', 'playWithBots');
});

Route::prefix('/matches')->controller(MatchController::class)->group(function () {
    Route::post('/ready', 'readyForPlay');
    Route::post('/start', 'matchStart');
    Route::post('/bet', 'betAmount');
    Route::post('/one-more-card', 'oneMoreCard');
    Route::post('/catch-three-card', 'catchThreeCard');
    Route::post('/next-time-banker', 'nextTimeBanker');
    Route::post('/amount-change-request', 'amountChangeRequest');
    Route::post('/quit-match', 'quitMatch');
    Route::post('/cancel-quit-match', 'cancelQuitMatch');
});

Route::prefix('/messages')->controller(MessageController::class)->group(function () {
    Route::post('/public', 'publicMessage');
    Route::post('/private', 'privateMessage');
});

Route::prefix('/gift')->controller(GiftController::class)->group(function () {
    Route::post('/buy-gift', 'buyGift');
    Route::post('/give-gift', 'GiveGift');
});

Route::prefix('/cash-out-request')->controller(CashOutRequestController::class)->group(function () {
    Route::post('/', 'index');
    Route::post('/create-cash-out', 'createCashOut');
});

Route::prefix('/room-type')->controller(RoomTypeController::class)->group(function () {
    Route::post('/list', 'list');
});
