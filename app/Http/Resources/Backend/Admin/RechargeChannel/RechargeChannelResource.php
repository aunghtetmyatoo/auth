<?php

namespace App\Http\Resources\Backend\Admin\RechargeChannel;

use Illuminate\Http\Resources\Json\JsonResource;

class RechargeChannelResource extends JsonResource
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
            'min_per_transaction' => $this->min_per_transaction,
            'max_per_transaction' => $this->max_per_transaction,
            'max_daily' => $this->max_daily,
            'handling_fee' => $this->handling_fee,
            'telegram_channel_id' => $this->telegram_channel_id,
            'requests_expired_in' => $this->requests_expired_in,
            'status' => $this->status,
            'address' => $this->address,
            'currency' => $this->exchange_currency,
        ];
    }
}
