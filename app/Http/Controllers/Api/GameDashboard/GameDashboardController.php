<?php

namespace App\Http\Controllers\Api\GameDashboard;

use App\Actions\Endpoint;
use App\Constants\ServerPath;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GameDashboardController extends Controller
{
    public function __construct(private Endpoint $endpoint)
    {
    }

    public function playGameTypeInfo(Request $request)
    {
        return $this->endpoint->handle(config('api.url.card'), ServerPath::PLAY_GAME_TYPE_INFO, [
            'user_id' => $request->user_id,
            'game_type_id' => $request->game_type_id,
            'chart_from_month' => $request->chart_from_month,
            'chart_to_month' => $request->chart_to_month,
            'chart_year' => $request->chart_year,
        ]);
    }
}
