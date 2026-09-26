<?php

namespace App\Modules\Order\Database\Seeders;

use App\Modules\Order\Entities\Bank;
use Illuminate\Database\Seeder;

class ParsianSeeder extends Seeder
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
                'title' => 'تجارت الکترونیک پارسیان (تاپ)',
                'icon' => 'parsian.png',
                'status' => 0,
                'bank_type' => 'parsian',
                'config' => null,
            ]
        ];
        Bank::insert($bank);
    }
}
