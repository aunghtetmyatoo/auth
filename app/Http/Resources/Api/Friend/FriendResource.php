<?php

namespace App\Http\Resources\Api\Friend;

use App\Constants\Status;
use Illuminate\Http\Resources\Json\JsonResource;

class FriendResource extends JsonResource
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
            'id' => $this->friend->id,
            'name' => $this->friend->name,
            'reference_id' => $this->friend->reference_id,
            'confirm_status' => $this->confirm_status,
            'is_online' => 1,
            'is_playing' => ($this->friend->play == Status::PLAYING) ? 1 : 0,
        ];
    }
}
