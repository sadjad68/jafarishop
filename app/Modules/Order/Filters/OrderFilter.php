<?php

namespace App\Modules\Order\Filters;

use Illuminate\Database\Eloquent\Builder;
use App\Modules\General\Helper\DateHelper;

class OrderFilter
{
    public function apply(Builder $query, $filters)
    {
        if (isset($filters['full_name'])) {
            $query->whereHas('user', function (Builder $query2) use ($filters) {
                $query2->where('full_name', 'LIKE', '%' . $filters['full_name'] . '%');
            });
        }
        if (isset($filters['mobile'])) {
            $query->whereHas('user', function (Builder $query2) use ($filters) {
                $query2->where('mobile', 'LIKE', '%' . $filters['mobile'] . '%');
            });
        }
        if (isset($filters['id'])) {
            $query->where('id', $filters['id']);
        }
        if (isset($filters['transactionId'])) {
            $query->where('transaction_info->post->transactionId', $filters['transactionId']);
        }
        if (isset($filters['shipping_method_id'])) {
            $query->where('shipping_method_id', $filters['shipping_method_id']);
        }
        if (isset($filters['shipping_status_id'])) {
            $query->where('shipping_status_id', $filters['shipping_status_id']);
        }
        if (isset($filters['order_status'])) {
            $query->where('order_status', $filters['order_status']);
        }
        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }
        if (!empty($filters['from_date']) || !empty($filters['to_date'])) {

            $from_date = !empty($filters['from_date']) ? DateHelper::convertDate($filters['from_date']) : null;
            $to_date = !empty($filters['to_date']) ? DateHelper::convertDate($filters['to_date']) : null;

            if (!empty($from_date) && !empty($to_date)) {
                $query->whereBetween('created_at', [
                    $from_date . ' 00:00:00',
                    $to_date . ' 23:59:59',
                ]);
            } elseif (!empty($from_date)) {
                $query->where('created_at', '>=', $from_date . ' 00:00:00');
            } elseif (!empty($to_date)) {
                $query->where('created_at', '<=', $to_date . ' 23:59:59');
            }
        }

        return $query;
    }
}
