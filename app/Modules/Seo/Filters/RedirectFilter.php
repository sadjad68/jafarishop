<?php

namespace App\Modules\Seo\Filters;

use Illuminate\Database\Eloquent\Builder;
use App\Modules\Product\Services\ProductService;

class RedirectFilter
{
    public function apply(Builder $query, $filters)
    {

        if (isset($filters['old_address'])) {
            $old_address = ltrim(rtrim($filters['old_address'], '/'), '/') ?: "/";
            $query->where('old_address', $old_address);
        }
        if (isset($filters['new_address'])) {
            $new_address = ltrim(rtrim($filters['new_address'], '/'), '/') ?: "/";
            $query->where('new_address', $new_address);
        }
        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        return $query;
    }
}
