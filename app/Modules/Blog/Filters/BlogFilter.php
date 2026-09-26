<?php

namespace App\Modules\Blog\Filters;

use Illuminate\Database\Eloquent\Builder;

class BlogFilter
{
    public function apply(Builder $query, $filters)
    {
        if (isset($filters['title'])) {
            $title = $filters['title'];
            $query->where('title', 'like', '%' . $title . '%');
        }

        if (isset($filters['url'])) {
            $url = $filters['url'];
            $query->where('url', 'like', '%' . $url . '%');
        }
        return $query;
    }
}
