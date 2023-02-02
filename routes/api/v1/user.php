<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RechargeRequest\RechargeRequestController;

Route::prefix('/recharge-request')->controller(RechargeRequestController::class)->group(function(){
    Route::post('/', 'index');

});
// For Game Dashboard
Route::prefix('/recharge-request')->middleware('auth:player')->controller(RechargeRequestController::class)->group(function () {
    Route::post('/enquiry-usdt', 'enquiryUsdt');
    Route::post('/usdt', 'usdt');
    Route::post('/cancelled/usdt', 'cancelledUsdt');
    
    Route::post('/enquiry-kbz', 'enquiryKbz');
    Route::post('/kbz', 'kbzPay');
    Route::post('/cancelled/kbz', 'cancelledKbz');
    Route::post('/create-recharge', 'createRecharge');
});



