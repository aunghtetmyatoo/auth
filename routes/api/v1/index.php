<?php

use App\Http\Controllers\Api\GameTypeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\RefreshTokenController;
use App\Http\Controllers\Payment\BankAccountController;
use App\Http\Controllers\Payment\TransactionController;

Route::prefix('register')->controller(RegisterController::class)->group(function () {
    Route::post('get/otp', 'getOtp');
    Route::post('verify/otp', 'verifyOpt')->middleware('token:mb_register_verify_otp');
    Route::post('/', 'register')->middleware('token:mb_register');
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

Route::prefix('/game-type')->controller(GameTypeController::class)->group(function () {
    Route::post('/list', 'list');
});
