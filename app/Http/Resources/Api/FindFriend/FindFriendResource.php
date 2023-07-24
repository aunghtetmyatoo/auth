<?php

namespace App\Http\Resources\Api\FindFriend;

use App\Constants\Status;
use Illuminate\Http\Resources\Json\JsonResource;

class FindFriendResource extends JsonResource
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
            'reference_id' => $this->reference_id,
            'is_online' => 1,
            'is_playing' => ($this->play == Status::PLAYING) ? 1 : 0,
        ];
    }
}
