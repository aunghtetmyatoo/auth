<?php

namespace App\Http\Controllers\Api\CardGames;

use Illuminate\Http\Request;
use App\Actions\Endpoint;
use App\Constants\ServerPath;
use App\Traits\Auth\ApiResponse;
use App\Http\Controllers\Controller;

class TicketMoneyController extends Controller
{
    use ApiResponse;

    public function __construct(private Endpoint $endpoint)
    {
    }

    public function index(Request $request)
    {
        return $this->endpoint->handle(config('api.url.card'), ServerPath::TICKET_MONEY, [
            'from_user_id' => auth()->user()->id,
            'amount' => $request->amount,
            'to_user_id' => $request->to_user_id,
            'game_type_id' => $request->game_type_id,
        ]);
    }
}
