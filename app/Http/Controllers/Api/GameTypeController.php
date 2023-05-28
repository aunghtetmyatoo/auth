<?php

namespace App\Http\Controllers\Api;

use App\Models\GameType;
use App\Traits\Auth\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\GameType\GameTypeResource;

class GameTypeController extends Controller
{
    use ApiResponse;

    public function index()
    {
        return $this->responseCollection(GameTypeResource::collection(GameType::all()));
    }
}
