<?php

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
