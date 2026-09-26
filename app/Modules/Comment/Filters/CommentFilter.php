<?php

namespace App\Modules\Comment\Filters;


use Illuminate\Database\Eloquent\Builder;
use App\Modules\General\Helper\ModuleUtils;



class CommentFilter
{

    public function apply($query, $filters)
    {
        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (!empty($filters['mobile'])) {
            $query->where('mobile', 'like', '%' . $filters['mobile'] . '%');
        }

        if ($filters['status'] !== null && $filters['status'] !== '') {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['commentable_type'])) {
            $query->where('commentable_type', $filters['commentable_type']);
        }

        if (!empty($filters['rate'])) {
            $query->where('rate', $filters['rate']);
        }

        if ($filters['reply_id'] === 'null') {
            $query->whereNull('reply_id');
        }

        if ($filters['reply_id'] === 'not_null') {
            $query->whereNotNull('reply_id');
        }


        require_once ModuleUtils::app_module_path('General/Helper/jdate.php');

        if(!empty($filters['publish_date_start'])){

            list($jd,$jm,$jy) = explode('/',$filters['publish_date_start']);

            list($gy,$gm,$gd) = jalali_to_gregorian($jy,$jm,$jd);

            $start = sprintf('%04d-%02d-%02d',$gy,$gm,$gd).' 00:00:00';

            $query->where('created_at','>=',$start);
        }

        if(!empty($filters['publish_date_end'])){

            list($jd,$jm,$jy) = explode('/',$filters['publish_date_end']);

            list($gy,$gm,$gd) = jalali_to_gregorian($jy,$jm,$jd);

            $end = sprintf('%04d-%02d-%02d',$gy,$gm,$gd).' 23:59:59';

            $query->where('created_at','<=',$end);
        }

        return $query;
    }

}
