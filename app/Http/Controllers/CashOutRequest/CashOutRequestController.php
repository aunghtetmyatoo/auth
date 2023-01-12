<?php

namespace App\Http\Controllers\CashOutRequest;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CashOutRequest\CashOutIndexRequest;
use App\Models\CashOutRequest;
use Illuminate\Http\Request;

class CashOutRequestController extends Controller
{
    public function index(CashOutIndexRequest $request)
    {

        CashOutRequest::create([
                "transaction_type_id" => $request->transaction_type_id,
                "account_name" => $request->account_name,
                "account_number" => $request->account_number,
                "amount" => $request->amount,
                "user_id" => $request->user_id,
                "status"  => $request->status,
                "status_updated_by" => $request->status_updated_by,
            ]);

    }
}
