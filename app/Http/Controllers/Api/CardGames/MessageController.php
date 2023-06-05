<?php

namespace App\Http\Controllers\Api\CardGames;

use App\Actions\Endpoint;
use Illuminate\Http\Request;
use App\Constants\ServerPath;
use App\Http\Controllers\Controller;

class MessageController extends Controller
{
    public function __construct(private Endpoint $endpoint)
    {
    }

    public function publicMessage(Request $request)
    {
        return $this->endpoint->handle(config('api.url.card'), ServerPath::PUBLIC, [
            'user_id' => auth()->user()->id,
            'room_id' => $request->room_id,
            'message' => $request->message,
        ]);
    }

    public function privateMessage(Request $request)
    {
        return $this->endpoint->handle(config('api.url.card'), ServerPath::PRIVATE, [
            'user_id' => auth()->user()->id,
            'to_user_id' => $request->to_user_id,
            'message' => $request->message,
        ]);
    }
}
