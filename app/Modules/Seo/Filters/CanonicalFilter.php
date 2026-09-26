<?php

namespace App\Modules\Seo\Filters;

use Illuminate\Database\Eloquent\Builder;
use App\Modules\Product\Services\ProductService;

class CanonicalFilter
{
    public function apply(Builder $query, $filters)
    {

        if (isset($filters['url'])) {
            $url = ltrim(rtrim($filters['url'], '/'), '/') ?: "/";
            $query->where('url', $url);
        }
        if (isset($filters['canonical'])) {
            $canonical = ltrim(rtrim($filters['canonical'], '/'), '/') ?: "/";

            $query->where('canonical', $canonical);
        }


        return $query;
    }
}
