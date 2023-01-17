<?php

use App\Http\Controllers\CashOutRequest\CashOutRequestController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GiftController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\RemoteController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\Play\PlayController;
use App\Http\Controllers\Match\MatchController;
use App\Http\Controllers\Table\TableController;
use App\Http\Controllers\Payment\TransactionController;
use App\Http\Controllers\RechargeRequest\RechargeRequestController;
use App\Http\Controllers\ShanKoeMee\TransferPlayController;
use App\Http\Controllers\TicketMoney\TicketMoneyController;

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

//Card_Games
Route::prefix('/transfers')->controller(TransferPlayController::class)->group(function () {
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
});

Route::prefix('/remotes')->controller(RemoteController::class)->group(function () {
    Route::post('/update-play-status', 'updatePlayStatus');
    Route::post('/update-game-coin', 'updateGameCoin');
    Route::post('/update-user-amount', 'updateUserAmount');
    Route::post('/create-game-type-user', 'createGameTypeUser');
});

Route::prefix('/recharge-request')->controller(RechargeRequestController::class)->group(function () {
    Route::post('/', 'index');
});

Route::prefix('/cash-out-request')->controller(CashOutRequestController::class)->group(function () {
    Route::post('/', 'index');
});

