<?php

namespace App\Services\Torob;

class TorobApiResponse
{
    public static function make(int $currentPage, int $total, int $maxPages, array $products): array
    {
        return [
            'api_version' => 'torob_api_v3',
            'current_page' => $currentPage,
            'total' => $total,
            'max_pages' => $maxPages,
            'products' => array_values($products),
        ];
    }
}
