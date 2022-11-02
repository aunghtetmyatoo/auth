<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

Route::prefix('register')->controller(RegisterController::class)->group(function () {
    Route::post('get/otp', 'getOtp');
    Route::post('verify/otp', 'verifyOpt')->middleware('token:mb_register_verify_otp');
    Route::post('/', 'register')->middleware('token:mb_register');
});

Route::prefix('login')->controller(LoginController::class)->group(function () {
    Route::post('/', 'playerLogin');
});
