<?php

namespace App\Http\Resources\Api\WithdrawRequest;

use Illuminate\Http\Resources\Json\ResourceCollection;

class WithdrawRequestCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'results' => $this->collection,
            'has_more' => $this->nextPageUrl() ? true : false
        ];
    }
}
