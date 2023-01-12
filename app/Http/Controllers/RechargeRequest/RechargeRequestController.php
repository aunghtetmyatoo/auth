<?php

namespace App\Http\Controllers\RechargeRequest;

use App\Actions\StoreFile;
use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RechargeRequest\RechargeIndexRequest;
use App\Models\RechargeRequest;
use Illuminate\Http\Request;

class RechargeRequestController extends Controller
{
    public function index(RechargeIndexRequest $request)
    {
        $store_file = new StoreFile('Image/Recharge/'.$request->user_id);
        $transaction_screenshot_path = $store_file->execute(file :$request->file('transaction_screenshot'),file_prefix: Status::RECHARGE );

        RechargeRequest::create([
            "user_id" => $request->user_id,
            "transaction_screenshot" => $transaction_screenshot_path,
            "status" => $request->status,
            "admin_id" => $request->admin_id,
            "payment_type_id" => $request->payment_type_id,
        ]);
    }
}
