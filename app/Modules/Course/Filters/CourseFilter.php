<?php

namespace App\Modules\Course\Filters;

use Illuminate\Database\Eloquent\Builder;

class CourseFilter
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
