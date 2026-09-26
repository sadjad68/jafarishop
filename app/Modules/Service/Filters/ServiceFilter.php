<?php

namespace App\Modules\Service\Filters;

use Illuminate\Database\Eloquent\Builder;

class ServiceFilter
{
    public function apply(Builder $query, $filters)
    {
        if (isset($filters['title'])) {
            $query->where('title', 'LIKE', '%' . $filters['title'] . '%');
        }

        if (isset($filters['parent_id'])) {
            $query->where('parent_id', $filters['parent_id']);
        }
        return $query;
    }
}
