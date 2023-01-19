<?php

namespace App\Http\Controllers\Play;

use Illuminate\Http\Request;
use App\Actions\HandleEndpoint;
use App\Constants\ServerPath;
use App\Http\Controllers\Controller;

class PlayController extends Controller
{
    public function __construct(private HandleEndpoint $handleEndpoint)
    {
    }

    public function playDirect(Request $request)
    {
        return $this->handleEndpoint->handle(server_path: ServerPath::PLAY_DIRECT, request: [
            'user_id' => auth()->user()->id,
            'game_type_id' => $request->game_type_id,
        ]);
    }

    public function playWithBots(Request $request)
    {
        return $this->handleEndpoint->handle(server_path: ServerPath::PLAY_WITH_BOT, request: [
            'user_id' => auth()->user()->id,
            'game_type_id' => $request->game_type_id,
            'room_name' => $request->room_name,
            'banker_amount' => $request->banker_amount,
        ]);
    }
}
