<?php

namespace App\Http\Controllers\Api;

use App\Models\GameCategory;
use App\Traits\Auth\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\GameCategory\GameCategoryResource;

class GameCategoryController extends Controller
{
    use ApiResponse;

    public function index()
    {
        return $this->responseCollection(GameCategoryResource::collection(GameCategory::all()));
    }
}
