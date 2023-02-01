<?php

namespace App\Http\Controllers\RoomType;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Actions\HandleEndpoint;
use App\Constants\ServerPath;

class RoomTypeController extends Controller
{
    public function __construct(private HandleEndpoint $handleEndpoint)
    {
    }

    public function list()
    {
        return $this->handleEndpoint->handle(server_path: ServerPath::ROOM_TYPE_LIST, request: [
        ]);
    }
}
