<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RemoteController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\RefreshTokenController;
use App\Http\Controllers\Deposite\DepositeController;
use App\Http\Controllers\Payment\BankAccountController;
use App\Http\Controllers\Payment\TransactionController;
use App\Http\Controllers\RechargeRequest\RechargeRequestController;
use App\Http\Controllers\TelegramController;

Route::prefix('register')->controller(RegisterController::class)->group(function () {
    Route::post('get/otp', 'getOtp');
    Route::post('verify/otp', 'verifyOpt')->middleware('token:mb_register_verify_otp');
    Route::post('/', 'register');
    // Route::post('/', 'register')->middleware('token:mb_register');
});

Route::prefix('login')->controller(LoginController::class)->group(function () {
    Route::post('/', 'playerLogin')->middleware('login');
});

Route::prefix('account')->middleware(["auth:player", "spam", "bank-account"])->group(function () {
    Route::get('/logout', [LoginController::class, 'logout']);
    Route::post('/payment/deposit', [TransactionController::class, 'deposit']);
});


Route::prefix('account')->middleware(["auth:player", "spam"])->group(function () {
    Route::post('/payment/add-info', [BankAccountController::class, 'addPaymentMethod']);
});


Route::post('/refresh-token', [RefreshTokenController::class, '__invoke'])->middleware("refresh_token");

Route::prefix('/deposite')->controller(DepositeController::class)->group(function () {
    Route::post('/', 'index');
});

Route::prefix('/remotes')->controller(RemoteController::class)->group(function () {
    Route::post('/update-play-status', 'updatePlayStatus');
    Route::post('/update-game-coin', 'updateGameCoin');
    Route::post('/update-user-amount', 'updateUserAmount');
    Route::post('/create-game-type-user', 'createGameTypeUser');
    Route::post('/add-history','addHistory');
    Route::post('/update-user-match-history','updateUserMatchHistory');
});

Route::prefix('/telegram')->controller(TelegramController::class)->group(function () {
    Route::post('/send-message', 'sendMessage');
    Route::post('/send-photo', 'sendPhoto');

});

// For Game Dashboard
Route::prefix('/recharge-request')->controller(RechargeRequestController::class)->group(function () {
    Route::post('/', 'index');
    Route::post('/create-recharge','createRecharge');
});



