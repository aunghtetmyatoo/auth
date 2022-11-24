<?php

namespace App\Http\Controllers\TicketMoney;

use App\Http\Controllers\Controller;
use App\Traits\Auth\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TicketMoneyController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $response = Http::post(config('api.server.card_games.end_point') . config('api.server.card_games.ticket_moneys.prefix'), [
            'from_user_id' => $request->from_user_id,
            'amount' => $request->amount,
            'to_user_id' => $request->to_user_id,
        ]);
        return json_decode($response, true);
    }
}
