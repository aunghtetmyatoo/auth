<?php

namespace App\Http\Controllers\CashOutRequest;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CashOutRequest\CashOutIndexRequest;
use App\Http\Resources\Api\CashOut\CashOutCollection;
use App\Models\CashOutRequest;
use App\Traits\Auth\ApiResponse;
use Illuminate\Http\Request;

class CashOutRequestController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $cashOutRequest = CashOutRequest::where(function ($query) use ($request) {

            $request->has('name')
            && $query->where('name', 'like', '%' . $request->name . '%');

        });

        return $this->responseCollection(new CashOutCollection ($cashOutRequest->paginate($request->per_page)));
    }

    public function createCashOut(CashOutIndexRequest $request)
    {

        CashOutRequest::create([
            "transaction_type_id" => $request->transaction_type_id,
            "account_name" => $request->account_name,
            "account_number" => $request->account_number,
            "amount" => $request->amount,
            "user_id" => auth()->user()->id,
            // "status"  => $request->status,
            // "status_updated_by" => $request->status_updated_by,
        ]);

        return $this->responseSucceed(message: "Add  Cash Out Request Successfully");
    }
}
