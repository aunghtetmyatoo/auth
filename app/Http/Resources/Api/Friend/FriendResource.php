<?php

namespace App\Http\Resources\Api\Friend;

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
            'user_name' => $this->user->name,
            'friend_name' => $this->friend->name,
            'friend_id' => $this->friend->id,
            'confirm_status' => $this->confirm_status,
        ];
    }
}
