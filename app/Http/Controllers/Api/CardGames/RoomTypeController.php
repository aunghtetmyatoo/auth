<?php

namespace App\Http\Controllers\Api\CardGames;

use App\Http\Controllers\Controller;
use App\Actions\HandleEndpoint;
use App\Constants\ServerPath;

class RoomTypeController extends Controller
{
    public function __construct(private HandleEndpoint $handleEndpoint)
    {
    }

    public function index()
    {
        return $this->handleEndpoint->handle(server_path: ServerPath::ROOM_TYPE_LIST, request: []);
    }
}
