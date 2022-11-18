<?php

use App\Http\Controllers\FriendController;
use App\Http\Controllers\Payment\TransactionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShanKoeMee\TransferToPlayController;

Route::controller(TransferToPlayController::class)->group(function () {
    Route::post('/transfer', 'transferToGame');
});

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
