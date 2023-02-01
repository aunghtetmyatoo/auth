<?php

namespace App\Http\Resources\Api\GameType;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Helpers\Pagination;

class GameTypeCollection extends ResourceCollection
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
