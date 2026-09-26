<?php

namespace App\Modules\Product\Filters;

use Illuminate\Database\Eloquent\Builder;

class ProductCategoryFilter
{
    public function apply(Builder $query, $filters)
    {
        if (isset($filters['title'])) {
            $query->where('title', 'like', '%' . $filters['title'] . '%');
        }
        if (isset($filters['url'])) {
            $query->where('url', 'like',  $filters['url']);
        }
        if (isset($filters['show_in_first_page'])) {
            $query->where('show_in_first_page', 'like',  $filters['show_in_first_page']);
        }
        return $query;
    }
}
