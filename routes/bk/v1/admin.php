<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RechargeChannelCrudController;
use App\Http\Controllers\Admin\RechargeRequestController;
use App\Http\Controllers\Admin\WithdrawChannelCrudController;

Route::prefix('recharge-requests')->controller(RechargeRequestController::class)->group(function () {
    Route::post('/', 'index');
    Route::post('create', 'create');
    Route::post('{recharge_request}/confirm', 'confirm');
    Route::post('{recharge_request}/reject', 'reject');
    Route::post('{recharge_request}/request', 'request');
    Route::post('{recharge_request}/complete', 'complete');
});

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
