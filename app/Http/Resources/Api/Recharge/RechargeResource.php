<?php

namespace App\Http\Resources\Api\Recharge;

use Carbon\Carbon;
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
            "recharge_channel_name"=>$this->recharge_channel->name,
            "Reference"=>$this->reference_id,
            'user_name' => $this->user->name,
            'phone' => $this->user->phone_number,
            "requested_amount" => $this->requested_amount,
            "status" => $this->status,
            'admin_name' => $this->admin_id ? $this->admin->name : '-',
            'expire_at' =>Carbon::now()->greaterThan($this->expired_at) ? "Expire" : $this->created_at->addMinutes(30)->format('Y-m-d H:i:s')
            ];
    }
}
