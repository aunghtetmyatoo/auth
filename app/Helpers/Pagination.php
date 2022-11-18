<?php

namespace App\Helpers;

class Pagination
{
    public static function getLinks($paginator): array
    {
        return [
            'links' => [
                'first' => $paginator->path() . '?page='  . 1,
                'last' => $paginator->path() . '?page='  . $paginator->lastPage(),
                'prev' => $paginator->previousPageUrl(),
                'next' => $paginator->nextPageUrl(),
            ],
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
            'has_more' => $paginator->nextPageUrl() ? true : false
        ];
    }
}
