<?php

namespace App\Http\Controllers\Play;

use Illuminate\Http\Request;
use App\Actions\HandleEndpoint;
use App\Http\Controllers\Controller;

class PlayController extends Controller
{
    public function __construct(private HandleEndpoint $handleEndpoint)
    {
    }

    public function playDirect(Request $request)
    {
        return $this->handleEndpoint->handle(server_name: "card_games", prefix: "plays", route_name: "direct", request: [
            'user_id' => auth()->user()->id,
            'game_type_id' => $request->game_type_id,
        ]);
    }
}
