<?php

namespace App\Modules\User\Filters;

use Illuminate\Database\Eloquent\Builder;

class UserFilter
{
    public function apply(Builder $query, $filters)
    {
        if (isset($filters['full_name'])) {
            $full_name = $filters['full_name'];
            $query->where('full_name', 'like', '%' . $full_name . '%');
        }

        if (isset($filters['mobile'])) {
            $mobile = $filters['mobile'];
            $query->where('mobile', 'like', '%' . $mobile . '%');
        }

        if (isset($filters['email'])) {
            $email = $filters['email'];
            $query->where('email', 'like', '%' . $email . '%');
        }

        if (isset($filters['type'])) {
            $type = $filters['type'];
            $query->whereHas('userTypes', function ($user_query) use ($type) {
                $user_query->where('type', $type);
            });
        }

        return $query;
    }
}
