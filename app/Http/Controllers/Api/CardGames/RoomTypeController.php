<?php

namespace App\Http\Controllers\Api\CardGames;

use App\Http\Controllers\Controller;
use App\Actions\Endpoint;
use App\Constants\ServerPath;

class RoomTypeController extends Controller
{
    public function __construct(private Endpoint $endpoint)
    {
    }

    public function index()
    {
        return $this->endpoint->handle(config('api.url.card'), ServerPath::ROOM_TYPE_LIST);
    }
}
