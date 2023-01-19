<?php

namespace App\Http\Controllers\Remote;

use App\Models\History;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\Auth\ApiResponse;
use App\Http\Controllers\Controller;

class RemoteHistoryController extends Controller
{
    use ApiResponse;

    public function addHistory(Request $request)
    {
        $history = new History;
        $history->transaction_type_id = $request->transaction_type_id;
        $history->historiable_id = $request->historiable_id;
        $history->historiable_type =  $request->historiable_type;
        $history->transactionable_id = $request->user_id;
        $history->transactionable_type = $request->user_model;
        $history->reference_id =  strtoupper(Str::random(15));
        $history->amount_before_transaction = $request->user_amount_before_transaction;
        $history->amount_after_transaction = $request->user_amount_after_transaction;
        $history->is_from = $request->is_from;
        $history->save();
    }
}
