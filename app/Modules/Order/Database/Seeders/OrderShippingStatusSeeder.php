<?php

namespace App\Modules\Order\Database\Seeders;

use App\Modules\Order\Entities\Bank;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Order\Entities\OrderShippingStatus;

class OrderShippingStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $status = [
            [
                'title' => 'در انتظار بررسی',
                'color' => '#3498db',
                'default' => 1,

            ],
            [
                'title' => 'درحال آماده سازی',
                'icon' => '#9b59b6',
                'status' => 0,

            ],
            [
                'title' => 'ارسال شد',
                'icon' => '#2ecc71',
                'status' => 0,

            ],

        ];
        OrderShippingStatus::insert($status);
    }
}
