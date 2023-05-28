<?php

namespace App\Http\Resources\Api\Profile;

use Illuminate\Http\Resources\Json\JsonResource;

class PlayerSettingResource extends JsonResource
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
            'user_id' => $this->user_id,
            'game_type_id' => $this->game_type_id,
            'sound_status' => $this->sound_status,
            'vibration_status' => $this->vibration_status,
            'challenge_status' => $this->challenge_status,
            'friend_status' => $this->friend_status,
        ];
    }
}
