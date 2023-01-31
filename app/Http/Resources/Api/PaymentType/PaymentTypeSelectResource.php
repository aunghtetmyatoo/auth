<?php

namespace App\Http\Resources\Api\PaymentType;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentTypeSelectResource extends JsonResource
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
            'value' => $this->id,
            'label' => $this->name,

        ];
    }
}
