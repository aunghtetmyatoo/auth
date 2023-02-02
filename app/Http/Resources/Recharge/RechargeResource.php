<?php

namespace App\Http\Resources\Recharge;

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
            "recharge_cahnnel_id"=>$this->recharge_channel_id,
            "requested_amount" => $this->requested_amount,
            "status" => $this->status,
            'user_name' => $this->user->name,
            'screenshot' => $this->screenshot,
            'status' => $this->status,
            'admin_name' => $this->admin_id ? $this->admin->name : '-',
            'time_expire' =>Carbon::now()->greaterThan($this->expired_at) ? "Expire" : $this->created_at->addMinutes(30)->format('Y-m-d H:i:s')
            ];
    }
}
