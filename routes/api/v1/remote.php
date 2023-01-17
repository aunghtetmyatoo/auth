<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Remote\RemoteGameTypeUserController;
use App\Http\Controllers\Remote\RemoteHistoryController;
use App\Http\Controllers\Remote\RemoteUserController;
use App\Http\Controllers\Remote\RemoteWinLoseLogController;

Route::prefix('/users')->controller(RemoteUserController::class)->group(function () {
    Route::post('/update-play-status', 'updatePlayStatus');
    Route::post('/update-user-amount', 'updateUserAmount');
});

Route::prefix('/game-type-users')->controller(RemoteGameTypeUserController::class)->group(function () {
    Route::post('/create', 'createGameTypeUser');
    Route::post('/update-game-coin', 'updateGameCoin');
});

Route::prefix('/histories')->controller(RemoteHistoryController::class)->group(function () {
    Route::post('/add-history','addHistory');
});

Route::prefix('/win-lose-logs')->controller(RemoteWinLoseLogController::class)->group(function () {
    Route::post('/update-user-match-history','updateUserMatchHistory');
});


