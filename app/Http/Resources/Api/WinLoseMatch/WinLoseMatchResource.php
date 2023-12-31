<?php

namespace App\Http\Resources\Api\WinLoseMatch;

use Illuminate\Http\Resources\Json\JsonResource;

class WinLoseMatchResource extends JsonResource
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
            'total_match' => $this->total_match,
        ];
    }
}
