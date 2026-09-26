<?php

namespace App\Modules\Certification\Filters;

use Illuminate\Database\Eloquent\Builder;

class CertificateFilter
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
