<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RechargeRequest\RechargeRequestController;
use App\Http\Controllers\WithdrawRequest\WithdrawRequestController;


// For Recharge
Route::prefix('/recharge-request')->middleware('auth:player')->controller(RechargeRequestController::class)->group(function () {
    Route::post('/', 'index');
    Route::post('/channels','channels');
    Route::post('/enquiry-usdt', 'enquiryUsdt');
    Route::post('/enquiry-kbz', 'enquiryKbz');
    Route::post('/usdt', 'usdt');
    Route::post('/kbz', 'kbzPay');
    Route::post('/cancelled/usdt', 'cancelledUsdt');
    Route::post('/cancelled/kbz', 'cancelledKbz');
});

Route::prefix('/withdraw-request')->controller(WithdrawRequestController::class)->group(function () {
    Route::post('/', 'index');
});
// For Withdraw
Route::prefix('/withdraw-request')->middleware('auth:player')->controller(WithdrawRequestController::class)->group(function(){
    Route::post('/channels','channels');
    Route::post('/enquiry-kbz','enquiryKbz');
    Route::post('/enquiry-wechat', 'enquiryWechat');
    Route::post('/enquiry-alipay','enquiryAliPay');
    Route::post('/enquiry-bankcard', 'enquiryBankCard');

    Route::post('/enquiry-thaibaht','enquiryThaiBaht');
    Route::post('/kbzpay','kbzPay');
    Route::post('/alipay', 'aliPay');
    Route::post('/thaibaht', 'thaiBaht');
    Route::post('/bank-card', 'bankCard');
    Route::post('/wechat', 'weChat');
    Route::post('/encryptname','encryptName');

});

