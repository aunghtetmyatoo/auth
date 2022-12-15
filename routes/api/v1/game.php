<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\Match\MatchController;
use App\Http\Controllers\Payment\TransactionController;
use App\Http\Controllers\Play\PlayController;
use App\Http\Controllers\ShanKoeMee\TransferPlayController;
use App\Http\Controllers\Table\TableController;
use App\Http\Controllers\TicketMoney\TicketMoneyController;

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

Route::prefix('/ticket-money')->controller(TicketMoneyController::class)->group(function () {
    Route::post('/', 'index');
});


Route::prefix('/tables')->controller(TableController::class)->group(function () {
    Route::post('/list', 'listPublicTable');
    Route::post('/create', 'createTable');
    Route::post('/join', 'joinTable');
    Route::post('/leave', 'leaveTable');
    Route::post('/invite', 'inviteFriend');
});


Route::prefix('/plays')->controller(PlayController::class)->group(function () {
    Route::post('/', 'playDirect');
});


Route::prefix('/matches')->controller(MatchController::class)->group(function () {
    Route::post('/start', 'matchStart');
    Route::post('/bet', 'betAmount');
    Route::post('/one-more-card', 'oneMoreCard');
    Route::post('/catch-three-card', 'catchThreeCard');
    Route::post('/quit-match','quitMatch');
});
