<?php

namespace App\Modules\Tag\Filters;

use Illuminate\Database\Eloquent\Builder;

class TagFilter
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
