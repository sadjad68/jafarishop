<?php

namespace App\Modules\Location\Filters;

use Illuminate\Database\Eloquent\Builder;

class AddressFilter
{
    public function apply(Builder $query, $filters)
    {
        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }


        return $query;
    }
}
