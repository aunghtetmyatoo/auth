<?php

use App\Http\Controllers\Api\GameDashboard\GameDashboardController;
use Illuminate\Support\Facades\Route;

// For Dashboard
// ->middleware('auth:player')

Route::prefix('/play-history')->controller(GameDashboardController::class)->group(function () {
    Route::post('game-type-info', 'playGameTypeInfo');
});



