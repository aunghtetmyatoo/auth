<?php

namespace App\Http\Resources\Api\RequestFriend;

use Illuminate\Http\Resources\Json\JsonResource;

class RequestFriendResource extends JsonResource
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
            'request_name' => $this->friend->name,
            'request_id' => $this->friend->id,
            'confirm_status' => $this->confirm_status,
        ];
    }
}
