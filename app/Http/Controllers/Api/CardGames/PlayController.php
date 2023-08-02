<?php

namespace App\Http\Controllers\Api\CardGames;

use Illuminate\Http\Request;
use App\Actions\Endpoint;
use App\Constants\ServerPath;
use App\Http\Controllers\Controller;

class PlayController extends Controller
{
    public function __construct(private Endpoint $endpoint)
    {
    }

    public function playDirect(Request $request)
    {
        return $this->endpoint->handle(config('api.url.card'), ServerPath::PLAY_DIRECT, [
            'user_id' => auth()->user()->id,
            'game_type_id' => $request->game_type_id,
            'room_id' => $request->room_id,
            'room_type_id' => $request->room_type_id,
        ]);
    }

    public function playWithBots(Request $request)
    {
        return $this->endpoint->handle(config('api.url.card'), ServerPath::PLAY_WITH_BOT, [
            'user_id' => auth()->user()->id,
            'game_type_id' => $request->game_type_id,
            'room_type_id' => $request->room_type_id,
            // 'room_name' => $request->room_name,
            // 'banker_amount' => $request->banker_amount,
        ]);
    }
}
