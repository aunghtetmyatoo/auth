<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RefreshTokenController;
use App\Http\Controllers\Payment\BankAccountController;

Route::prefix('register')->controller(RegisterController::class)->group(function () {
    Route::post('get/otp', 'getOtp');
    Route::post('verify/otp', 'verifyOpt')->middleware('token:mb_register_verify_otp');
    Route::post('/', 'register')->middleware('token:mb_register');
});

Route::prefix('login')->controller(LoginController::class)->group(function () {
    Route::post('/', 'playerLogin');
});

Route::prefix('account')->middleware(["auth:player"])->group(function () {
    Route::get('/logout', [LoginController::class, 'logout']);
    Route::post('/payment/add-info', [BankAccountController::class, 'addPaymentMethod']);
});

Route::post('/refresh-token', [RefreshTokenController::class, '__invoke']);
