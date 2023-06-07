<?php

namespace App\Http\Resources\Api\RechargeChannel;

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
            'name' => $this->name,
            'min_per_transaction' => $this->min_per_transaction,
            'max_per_transaction' => $this->max_per_transaction,
            'max_daily' => $this->max_daily,
            'handling_fee' => $this->handling_fee,
            'telegram_channel_id' => $this->telegram_channel_id,
            'status' => $this->status,
            'exchange_currency' => $this->exchange_currency,
        ];
    }
}
