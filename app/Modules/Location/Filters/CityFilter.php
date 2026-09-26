<?php

namespace App\Modules\Location\Filters;

use Illuminate\Database\Eloquent\Builder;

class CityFilter
{
    public function apply(Builder $query, $filters)
    {
        if (isset($filters['name'])) {
            $title = $filters['name'];
            $query->where('name', 'like', '%' . $title . '%');
        }


        return $query;
    }
}
