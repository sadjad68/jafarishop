<?php

namespace App\Modules\Product\Filters;

use Illuminate\Database\Eloquent\Builder;
use App\Modules\Product\Services\ProductService;

class ProductFilter
{
    public function apply(Builder $query, $filters)
    {

        if (isset($filters['title'])) {
            $query->whereSearch($filters['title']);
        }
        if (isset($filters['url'])) {
            $query->where('url', 'like', $filters['url']);
        }
        if (isset($filters['brand_id'])) {
            $query->where('brand_id', $filters['brand_id']);
        }
        if (isset($filters['creator_id'])) {
            $query->where('creator_id', $filters['creator_id']);
        }
        if (isset($filters['category_id'])) {

            $query->whereHas('categories', function ($query2) use ($filters) {
                $query2->where("product_category_id", $filters['category_id']);
            });
        }
        if (isset($filters['show_in_first_page'])) {
            $query->where('show_in_first_page', 'like', $filters['show_in_first_page']);
        }
        if (isset($filters['stock_status'])) {
            if ($filters['stock_status'] == 'out_of_stock') {
                $query->where('stock', 0);
            } else {
                $query->where('stock', '>', 0);
            }
        }
        return $query;
    }
}
