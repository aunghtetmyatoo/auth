<?php

namespace App\Http\Resources\Recharge;

use Illuminate\Http\Resources\Json\JsonResource;

class RechargeResource extends JsonResource
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
            'user_id' => $this->user_id,
            'transaction_screenshot' => $this->transaction_screenshot,
            'status' => $this->status,
            'admin_id' => $this->admin_id,
            'payment_type_id' => $this->payment_type_id,
            ];
    }
}
