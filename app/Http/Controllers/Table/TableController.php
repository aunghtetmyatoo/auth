<?php

namespace App\Http\Controllers\Table;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TableController extends Controller
{
    public function listPublicTable(Request $request)
    {
        $response = Http::post(config('api.server.card_games.end_point') . config('api.server.card_games.tables.prefix') . config('api.server.card_games.tables.list'), [
            'per_paginate' => $request->per_paginate,
        ]);
        return json_decode($response, true);
    }

    public function createTable(Request $request)
    {
        $response = Http::post(config('api.server.card_games.end_point') . config('api.server.card_games.tables.prefix') . config('api.server.card_games.tables.create'), [
            'user_id' => auth()->user()->id,
            'name' => $request->name,
            'room_type_id' => $request->room_type_id,
            'game_type_id' => $request->game_type_id,
            'banker_amount' => $request->banker_amount,
            'privacy'  => $request->privacy,
        ]);
        return json_decode($response, true);
    }

    public function joinTable(Request $request)
    {
        $response = Http::post(config('api.server.card_games.end_point') . config('api.server.card_games.tables.prefix') . config('api.server.card_games.tables.join'), [
            'user_id' => auth()->user()->id,
            'coin' => $request->coin,
            'room_id' => $request->room_id,
            'game_type_id' => $request->game_type_id,
            'reference_id' => $request->reference_id,
        ]);
        return json_decode($response, true);
    }

    public function leaveTable(Request $request)
    {
        $response = Http::post(config('api.server.card_games.end_point') . config('api.server.card_games.tables.prefix') . config('api.server.card_games.tables.leave'), [
            'user_id' => auth()->user()->id,
            'room_id' => $request->room_id,
        ]);
        return json_decode($response, true);
    }

    public function inviteFriend(Request $request)
    {
        $response = Http::post(config('api.server.card_games.end_point') . config('api.server.card_games.tables.prefix') . config('api.server.card_games.tables.invite'), [
            'user_id' => auth()->user()->id,
            'friend_id' => $request->friend_id,
        ]);
        return json_decode($response, true);
    }
}
