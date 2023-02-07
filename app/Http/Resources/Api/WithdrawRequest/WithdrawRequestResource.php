<?php

namespace App\Http\Resources\Api\WithdrawRequest;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class WithdrawRequestResource extends JsonResource
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
            "withdraw_channel_name" => $this->withdraw_channel->name,
            "Reference" => $this->reference_id,
            'user_name' => $this->user->name,
            'phone' => $this->user->phone_number,
            "amount" => $this->amount,
            "status" => $this->status,
            'admin_name' => $this->admin_id ? $this->admin->name : '-',
            'payee' => $this->payee,

        ];
    }
}
