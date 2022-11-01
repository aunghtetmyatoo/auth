<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;

Route::prefix('register')->controller(RegisterController::class)->group(function () {
    Route::post('get/otp', 'getOtp');
    Route::post('verify/otp', 'verifyOpt')->middleware('token:mb_register_verify_otp');
});
