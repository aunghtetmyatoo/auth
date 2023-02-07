<?php

namespace App\Http\Resources\Api\WithdrawChannel;

use Illuminate\Http\Resources\Json\JsonResource;

class WithdrawChannelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return[
            'id' => $this->id,
            'channel_name' => $this->name,
        ];
    }
}
