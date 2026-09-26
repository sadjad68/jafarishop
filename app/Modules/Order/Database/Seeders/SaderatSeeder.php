<?php

namespace App\Modules\Order\Database\Seeders;

use App\Modules\Order\Entities\Bank;
use Illuminate\Database\Seeder;

class SaderatSeeder extends Seeder
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
                'title' => 'صادرات',
                'icon' => 'saderat.jpg',
                'status' => 0,
                'bank_type' => 'saderat',
                'config' => null,
            ]
        ];
        Bank::insert($bank);
    }
}
