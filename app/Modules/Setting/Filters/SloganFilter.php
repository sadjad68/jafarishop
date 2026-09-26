<?php

namespace App\Modules\Setting\Filters;

use Illuminate\Database\Eloquent\Builder;

class SloganFilter
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
