<?php

namespace App\Modules\Order\Filters;

use Illuminate\Database\Eloquent\Builder;

class OrderShippingStatusFilter
{
    public function apply(Builder $query, $filters)
    {
        if (isset($filters['title'])) {
            $title = $filters['title'];
            $query->where('title', 'like', '%' . $title . '%');
        }


        return $query;
    }
}
