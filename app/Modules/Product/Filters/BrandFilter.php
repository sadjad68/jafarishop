<?php

namespace App\Modules\Product\Filters;

use Illuminate\Database\Eloquent\Builder;

class BrandFilter
{
    public function apply(Builder $query, $filters)
    {
        if (isset($filters['title'])) {
            $query->where('title', 'like', '%' . $filters['title'] . '%');
        }
        if (isset($filters['url'])) {
            $query->where('url', 'like',  $filters['url']);
        }
        return $query;
    }
}
