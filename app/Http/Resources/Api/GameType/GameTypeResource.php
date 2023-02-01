<?php

namespace App\Http\Resources\Api\GameType;

use Illuminate\Http\Resources\Json\JsonResource;

class GameTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'game_category_id' => $this->game_category_id,
            'game_category_name' => $this->game_category->name,
        ];
    }
}
