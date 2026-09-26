<?php

namespace App\Modules\Service\Filters;

use Illuminate\Database\Eloquent\Builder;

class WorkSampleFilter
{
    public function apply(Builder $query, $filters)
    {
        if (isset($filters['title'])) {
            $title = $filters['title'];
            $query->where('title', 'like', '%' . $title . '%');
        }
        if (isset($filters['service_id'])) {
            $query->whereHas('services', function (Builder $query2) use ($filters) {
                $query2->where('id', $filters['service_id']);
            });

        }
        return $query;
    }
}
