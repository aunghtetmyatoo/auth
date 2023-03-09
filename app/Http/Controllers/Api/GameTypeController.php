<?php

namespace App\Http\Controllers\Api;

use App\Models\GameType;
use App\Services\Crypto\DataKey;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\GameType\GameTypeResource;

class GameTypeController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        $response =  GameTypeResource::collection(GameType::all());

        return response()->json((new DataKey())->encrypt(json_encode($response)));
    }
}
