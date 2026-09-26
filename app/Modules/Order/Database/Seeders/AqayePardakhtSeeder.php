<?php

namespace App\Modules\Order\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Order\Entities\Bank;

class AqayePardakhtSeeder extends Seeder
{
    public function run()
    {
        $bank = [
            [
                'title' => 'آقای پرداخت',
                'icon' => 'aqayepardakht.png',
                'status' => 0,
                'bank_type' => 'aqayepardakht',
                'config' => null,
            ]
        ];

        Bank::insert($bank);
    }
}
