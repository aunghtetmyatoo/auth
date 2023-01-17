<?php

namespace App\Http\Resources\Api\CashOut;

use Illuminate\Http\Resources\Json\JsonResource;

class CashOutResource extends JsonResource
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
            'transaction_type_id' => $this->transaction_type_id,
            'account_name' => $this->account_name,
            'account_number' => $this->account_number,
            'amount' => $this->amount,
            'status' => $this->status,
            'admin_id' => $this->admin_id,
            ];
    }
}
