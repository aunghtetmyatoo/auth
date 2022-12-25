<?php

namespace App\Http\Controllers\Table;

use Illuminate\Http\Request;
use App\Actions\HandleEndpoint;
use App\Http\Controllers\Controller;

class TableController extends Controller
{
    public function __construct(private HandleEndpoint $handleEndpoint)
    {
    }

    public function listPublicTable(Request $request)
    {
        return $this->handleEndpoint->handle(server_name: "card_games", prefix: "tables", route_name: "list", request: [
            'user_id' => auth()->user()->id,
            'per_paginate' => $request->per_paginate,
        ]);
    }

    public function create(Request $request)
    {
        return $this->handleEndpoint->handle(server_name: "card_games", prefix: "tables", route_name: "create", request: [
            'user_id' => auth()->user()->id,
            'name' => $request->name,
            'game_type_id' => $request->game_type_id,
            'banker_amount' => $request->banker_amount,
            'privacy'  => $request->privacy,
        ]);
    }

    public function join(Request $request)
    {
        return $this->handleEndpoint->handle(server_name: "card_games", prefix: "tables", route_name: "join", request: [
            'user_id' => auth()->user()->id,
            'room_id' => $request->room_id,
            'game_type_id' => $request->game_type_id,
            'reference_id' => $request->reference_id,
        ]);
    }

    public function leave(Request $request)
    {
        return $this->handleEndpoint->handle(server_name: "card_games", prefix: "tables", route_name: "leave", request: [
            'user_id' => auth()->user()->id,
            'room_id' => $request->room_id,
        ]);
    }

    public function inviteFriend(Request $request)
    {
        return $this->handleEndpoint->handle(server_name: "card_games", prefix: "tables", route_name: "invite", request: [
            'from_invite_id' => auth()->user()->id,
            'to_invite_id' => $request->to_invite_id,
            'room_id' => $request->room_id,
        ]);
    }

    public function kickOut(Request $request)
    {
        return $this->handleEndpoint->handle(server_name: "card_games", prefix: "tables", route_name: "kick_out", request: [
            'user_id' => auth()->user()->id,
            'room_id' => $request->room_id,
            'kick_user_id' => $request->kick_user_id,
        ]);
    }
}
