<?php

namespace App\Http\Controllers\RechargeRequest;

use App\Actions\StoreFile;
use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RechargeRequest\RechargeIndexRequest;
use App\Models\RechargeRequest;
use App\Traits\Auth\ApiResponse;
use Illuminate\Http\Request;

class RechargeRequestController extends Controller
{
    use ApiResponse;

    public function index(RechargeIndexRequest $request)
    {
        $store_file = new StoreFile('Image/Recharge/'.$request->user_id);
        $transaction_screenshot_path = $store_file->execute(file :$request->file('transaction_screenshot'),file_prefix: Status::RECHARGE );

        RechargeRequest::create([
            "user_id" => auth()->user()->id,
            "transaction_screenshot" => $transaction_screenshot_path,
            "payment_type_id" => $request->payment_type_id,
            // "status" => $request->status,
            // "admin_id" => $request->admin_id,
        ]);

        return $this->responseSucceed(message: "Add Recharge Request Successfully");
    }
}