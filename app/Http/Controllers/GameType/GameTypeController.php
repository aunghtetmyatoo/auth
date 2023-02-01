<?php

namespace App\Http\Controllers\GameType;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\GameType\GameTypeResource;
use App\Models\GameType;
use Illuminate\Http\Request;
use App\Actions\HandleEndpoint;

class GameTypeController extends Controller
{
    public function __construct(private HandleEndpoint $handleEndpoint)
    {
    }

    public function list()
    {
        return GameTypeResource::collection(GameType::all());
    }
}
