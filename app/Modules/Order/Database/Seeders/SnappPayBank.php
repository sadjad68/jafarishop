<?php

namespace App\Modules\Order\Database\Seeders;

use App\Modules\Order\Entities\Bank;
use Illuminate\Database\Seeder;

class SnappPayBank extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bank = [
            [
                'title' => 'اسنپ پی',
                'icon' => 'snapp-pay.png',
                'status' => 0,
                'bank_type' => 'snappay',
                'config' => null,
            ]
        ];
        Bank::insert($bank);
    }
}
