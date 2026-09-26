<?php

namespace App\Modules\Order\Database\Seeders;

use App\Modules\Order\Entities\Bank;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class BankSeeder extends Seeder
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
                'title' => 'زرین پال',
                'icon' => 'zarinPal.png',
                'status' => 0,
                'bank_type' => 'zarinPal',
                'config' => null,
            ],
            [
                'title' => 'سپ',
                'icon' => 'sep.png',
                'status' => 0,
                'bank_type' => 'sep',
                'config' => null,
            ],
            [
                'title' => 'سداد',
                'icon' => 'sadad.png',
                'status' => 0,
                'bank_type' => 'sadad',
                'config' => null,
            ],

        ];
        Bank::insert($bank);
    }
}
