<?php

namespace App\Modules\Order\Database\Seeders;

use App\Modules\Order\Entities\Bank;
use Illuminate\Database\Seeder;

class ZibalSeeder extends Seeder
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
                'title' => 'زیبال',
                'icon' => 'zibal.svg',
                'status' => 0,
                'bank_type' => 'zibal',
                'config' => null,
            ]
        ];
        Bank::insert($bank);
    }
}
