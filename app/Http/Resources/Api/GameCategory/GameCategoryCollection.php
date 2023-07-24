<?php

namespace App\Http\Resources\Api\GameCategory;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Helpers\Pagination;

class GameCategoryCollection extends ResourceCollection
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
