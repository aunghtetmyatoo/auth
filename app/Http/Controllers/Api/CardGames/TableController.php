<?php

namespace App\Http\Controllers\Api\CardGames;

use Illuminate\Http\Request;
use App\Actions\Endpoint;
use App\Constants\ServerPath;
use App\Http\Controllers\Controller;

class TableController extends Controller
{
    public function __construct(private Endpoint $endpoint)
    {
    }

    public function listPublicTable(Request $request)
    {
        return $this->endpoint->handle(config('api.url.card'), ServerPath::TABLES_LIST, [
            'user_id' => auth()->user()->id,
            'room_type_id' => $request->room_type_id,
        ]);
    }

    public function ready(Request $request)
    {
        return $this->endpoint->handle(config('api.url.card'), ServerPath::READY_TABLE, [
            'room_id' => $request->room_id,
            'user_id' => auth()->user()->id,
        ]);
    }

    public function create(Request $request)
    {
        return $this->endpoint->handle(config('api.url.card'), ServerPath::CREATE_TABLE, [
            'user_id' => auth()->user()->id,
            'name' => $request->name,
            'game_type_id' => $request->game_type_id,
            'banker_amount' => $request->banker_amount,
            'privacy'  => $request->privacy,
            'is_side_bettor' => $request->is_side_bettor,
        ]);
    }

    public function join(Request $request)
    {
        return $this->endpoint->handle(config('api.url.card'), ServerPath::JOIN_TABLE, [
            'user_id' => auth()->user()->id,
            'room_id' => $request->room_id,
            'game_type_id' => $request->game_type_id,
            'secret' => $request->secret,
        ]);
    }

    public function joinBySideBettor(Request $request)
    {
        return $this->endpoint->handle(config('api.url.card'), ServerPath::JOIN_TABLE_SIDE_BETTOR, [
            'user_id' => auth()->user()->id,
            'room_id' => $request->room_id,
            'game_type_id' => $request->game_type_id,
            'reference_id' => $request->reference_id,
            'parent_id' => $request->parent_id,
        ]);
    }

    public function leave(Request $request)
    {
        return $this->endpoint->handle(config('api.url.card'), ServerPath::LEAVE_TABLE, [
            'user_id' => auth()->user()->id,
            'room_id' => $request->room_id,
        ]);
    }

    public function inviteFriend(Request $request)
    {
        return $this->endpoint->handle(config('api.url.card'), ServerPath::INVITE_FRIEND, [
            'from_id' => auth()->user()->id,
            'to_id' => $request->to_id,
            'room_id' => $request->room_id,
        ]);
    }

    public function kickOut(Request $request)
    {
        return $this->endpoint->handle(config('api.url.card'), ServerPath::KICK_OUT, [
            'user_id' => auth()->user()->id,
            'room_id' => $request->room_id,
            'kick_user_id' => $request->kick_user_id,
        ]);
    }
}
