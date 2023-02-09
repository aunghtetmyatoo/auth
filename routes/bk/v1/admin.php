<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RechargeChannelCrudController;
use App\Http\Controllers\Admin\RechargeRequestController;
use App\Http\Controllers\Admin\WithdrawChannelCrudController;
use App\Http\Controllers\Admin\WithdrawRequestController;

Route::prefix('recharge-channels')->controller(RechargeChannelCrudController::class)->group(function () {
    Route::post('/', 'index');
    Route::post('show', 'show');
    Route::post('create', 'store');
    Route::post('update', 'update');
    Route::post('delete', 'destroy');
});

Route::prefix('withdraw-channels')->controller(WithdrawChannelCrudController::class)->group(function () {
    Route::post('/', 'index');
    Route::post('show', 'show');
    Route::post('create', 'store');
    Route::post('update', 'update');
    Route::post('delete', 'destroy');
});
