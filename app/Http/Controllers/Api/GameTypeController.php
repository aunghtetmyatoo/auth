<?php

namespace App\Http\Controllers\Api;

use App\Models\GameType;
use App\Traits\Auth\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GameType\GameTypeRequest;
use App\Http\Resources\Api\GameType\GameTypeResource;

class GameTypeController extends Controller
{
    use ApiResponse;

    public function index(GameTypeRequest $request)
    {
        $game_types = GameType::where('game_category_id', $request->game_category_id)->get();

        return $this->responseCollection(GameTypeResource::collection($game_types));
    }
}
