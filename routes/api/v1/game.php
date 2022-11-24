<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\Payment\TransactionController;
use App\Http\Controllers\ShanKoeMee\TransferPlayController;

Route::prefix('/transactions')->controller(TransactionController::class)->group(function () {
    Route::post('/deposit', 'deposit');
    Route::post('/withdraw', 'withdraw');
});

Route::prefix('/friends')->controller(FriendController::class)->group(function () {
    Route::post('/', 'index');
    Route::post('/request-list', 'requestList');
    Route::post('/add', 'addFriend');
    Route::post('/confirm', 'confirmFriend');
    Route::post('/cancel', 'cancelFriend');
    Route::post('/unfriend', 'unfriend');
});

//Card_Games
Route::prefix('/transfers')->controller(TransferPlayController::class)->group(function () {
    Route::post('to-game', 'transferToGame');
    Route::post('from-game', 'transferFromGame');
});

Route::prefix('/tables')->controller(TableController::class)->group(function () {
    Route::post('/list', 'listPublicTable')->middleware('InvalidInviteInNoFriend');
    Route::post('/create', 'createTable')->middleware(['check_coin_create_and_join_room', 'check_playing_user']);
    Route::post('/join', 'joinTable')->middleware(['check_coin_create_and_join_room', 'check_playing_user']);
    Route::post('/leave', 'leaveTable');
    Route::post('/invite', 'inviteFriend')->middleware('InvalidInviteInNoFriend');
});

Route::prefix('/ticket-money')->controller(TicketMoneyController::class)->group(function () {
    Route::post('/', 'index')->middleware('CheckCoinForTicketMoney');
});

Route::prefix('/plays')->controller(PlayController::class)->group(function () {
    Route::post('/', 'playDirect');
});
