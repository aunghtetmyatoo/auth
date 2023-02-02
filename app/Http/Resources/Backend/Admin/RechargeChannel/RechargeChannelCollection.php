<?php

namespace App\Http\Resources\Backend\Admin\RechargeChannel;

use App\Helpers\Pagination;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RechargeChannelCollection extends ResourceCollection
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
            'data' => $this->collection,
            ...Pagination::getLinks($this),
        ];
    }
}
