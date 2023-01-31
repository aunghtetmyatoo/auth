<?php

use App\Http\Controllers\PaymentType\PaymentTypeController;
use App\Http\Controllers\RechargeRequest\RechargeRequestController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Remote\RemoteUserController;
use App\Http\Controllers\Remote\RemoteHistoryController;
use App\Http\Controllers\Remote\RemoteTransactionController;
use App\Http\Controllers\Remote\RemoteGameTypeUserController;
use App\Http\Controllers\Remote\RemoteWinLoseMatchController;

Route::prefix('/users')->controller(RemoteUserController::class)->group(function () {
    Route::post('/update-play-status', 'updatePlayStatus');
    Route::post('/update-user-amount', 'updateUserAmount');
});

Route::prefix('/game-type-users')->controller(RemoteGameTypeUserController::class)->group(function () {
    Route::post('/create', 'createGameTypeUser');
    Route::post('/update-game-coin', 'updateGameCoin');
});

Route::prefix('/histories')->controller(RemoteHistoryController::class)->group(function () {
    Route::post('/add-history', 'addHistory');
});

Route::prefix('/win-lose-matches')->controller(RemoteWinLoseMatchController::class)->group(function () {
    Route::post('/bettor', 'updateBettor');
    Route::post('/banker-bet-coin', 'updateBankerBetCoin');
    Route::post('/banker-win-lose-coin', 'updateBankerWinLoseCoin');
    Route::post('/banker-win-lose-match', 'updateBankerWinLoseMatch');
});

Route::prefix('/transactions')->controller(RemoteTransactionController::class)->group(function () {
    Route::post('/player', 'playerTransaction');
    Route::post('/bot', 'botTransaction');
});

// For Game Dashboard
Route::prefix('/recharge-request')->controller(RechargeRequestController::class)->group(function () {
    Route::post('/', 'index');
    Route::post('/create-recharge','createRecharge');
});

Route::prefix('/payment-type')->controller(PaymentTypeController::class)->group(function () {
    Route::post('/select', 'Select');
});

