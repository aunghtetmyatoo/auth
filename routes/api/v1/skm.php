<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShanKoeMee\TransferToPlayController;

Route::controller(TransferToPlayController::class)->group(function () {
    Route::post('/transfer', 'transferToGame');
});
