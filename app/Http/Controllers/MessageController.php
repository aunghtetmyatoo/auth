<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Actions\HandleEndpoint;
use App\Traits\Auth\ApiResponse;

class MessageController extends Controller
{
    use ApiResponse;

    public function __construct(private HandleEndpoint $handleEndpoint)
    {
    }

    public function publicMessage(Request $request)
    {
        return $this->handleEndpoint->handle(server_name: "card_games", prefix: "messages", route_name: "public", request: [
            'user_id' => auth()->user()->id,
            'room_id' => $request->room_id,
            'message' => $request->message,
        ]);
    }

    public function privateMessage(Request $request)
    {
        return $this->handleEndpoint->handle(server_name: "card_games", prefix: "messages", route_name: "private", request: [
            'user_id' => auth()->user()->id,
            'to_user_id' => $request->to_user_id,
            'message' => $request->message,
        ]);
    }
}
