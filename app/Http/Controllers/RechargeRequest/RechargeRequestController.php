<?php

namespace App\Http\Controllers\RechargeRequest;

use App\Actions\StoreFile;
use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RechargeRequest\RechargeCreateRequest;
use App\Http\Resources\Recharge\RechargeCollection;
use App\Models\RechargeRequest;
use App\Traits\Auth\ApiResponse;
use Illuminate\Http\Request;

class RechargeRequestController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $rechargeRequest = RechargeRequest::where(function ($query) use ($request) {
            $request->has('user_id') && $request->user_id != null
            && $query->where('user_id',$request->user_id);
        });

        return new RechargeCollection ($rechargeRequest->paginate($request->perPage ? $request->perPage : 5));
    }

    public  function createRecharge(RechargeCreateRequest $request)
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
