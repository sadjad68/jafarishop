<?php

namespace App\Modules\User\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Modules\User\Entities\User;

class UsersExport implements FromCollection
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $request = $this->request;

        $query = User::query();

        // اگر کاربر آدرس دارد
        if (!empty($request['has_address']) && $request['has_address'] == 1) {
            $query->whereHas('addresses');
        }

        // شمارش سفارشات
        if ($request['min_orders'] !== null || $request['max_orders'] !== null) {
            $query->withCount('orders');

            // اگر هر دو صفر باشند => فقط کاربرانی که هیچ سفارشی ندارند
            if ($request['min_orders'] == 0 && $request['max_orders'] == 0) {
                $query->having('orders_count', '=', 0);
            } else {
                if ($request['min_orders'] !== null) {
                    $query->having('orders_count', '>=', (int)$request['min_orders']);
                }
                if ($request['max_orders'] !== null) {
                    $query->having('orders_count', '<=', (int)$request['max_orders']);
                }
            }
        }

        // بازه زمانی
        if ($request['start'] !== null && $request['end'] !== null) {
            $start = explode('/', $request['start']);
            $end = explode('/', $request['end']);

            $s = \jmktime(0, 0, 0, $start[1], $start[0], $start[2]);
            $e = \jmktime(0, 0, 0, $end[1], $end[0], $end[2]);

            $start_timer = Carbon::createFromTimestamp($s);
            $end_timer   = Carbon::createFromTimestamp($e);

            $query->whereBetween('created_at', [$start_timer, $end_timer]);
        }

        $users = $query->get();

        // هدر جدول
        $data_array[] = [
            "ردیف",
            "شناسه کاربر",
            "نام کاربر",
            "موبایل کاربر",
            "ایمیل کاربر",
            "تاریخ ثبت نام",
        ];

        // داده‌ها
        foreach ($users as $key => $user) {
            $data_array[] = [
                $key + 1,
                $user->id,
                $user->full_name,
                $user->mobile,
                $user->email,
                $user->date,
            ];
        }

        return collect($data_array);
    }
}
