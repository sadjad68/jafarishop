<?php

namespace App\Modules\Course\Filters;

use Illuminate\Database\Eloquent\Builder;

class SessionFilter
{
    public function apply(Builder $query, $filters)
    {
        if (isset($filters['title'])) {
            $title = $filters['title'];
            $query->where('title', 'like', '%' . $title . '%');
        }
        if (isset($filters['course_id'])) {
            $course_id = $filters['course_id'];
            $query->where('course_id', $course_id)->orderBy('sort','asc');
        }

        return $query;
    }
}
