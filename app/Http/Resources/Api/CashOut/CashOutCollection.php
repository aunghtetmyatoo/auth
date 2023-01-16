<?php

namespace App\Http\Resources\Api\CashOut;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CashOutCollection extends ResourceCollection
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
