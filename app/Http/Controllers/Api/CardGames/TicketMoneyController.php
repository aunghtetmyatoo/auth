<?php

namespace App\Http\Controllers\Api\CardGames;

use Illuminate\Http\Request;
use App\Actions\HandleEndpoint;
use App\Constants\ServerPath;
use App\Traits\Auth\ApiResponse;
use App\Http\Controllers\Controller;

class TicketMoneyController extends Controller
{
    use ApiResponse;

    public function __construct(private HandleEndpoint $handleEndpoint)
    {
    }

    public function index(Request $request)
    {
        return $this->handleEndpoint->handle(server_path: ServerPath::TICKET_MONEY, request: [
            'from_user_id' => $request->from_user_id,
            'amount' => $request->amount,
            'to_user_id' => $request->to_user_id,
        ]);
    }
}
