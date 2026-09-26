<?php

namespace App\Modules\Order\Database\Seeders;

use App\Modules\Order\Entities\Bank;
use Illuminate\Database\Seeder;

class IrDargahSeeder extends Seeder
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
                'title' => 'ایران درگاه',
                'icon' => 'irandargah.png',
                'status' => 0,
                'bank_type' => 'irandargah',
                'config' => null,
            ]
        ];
        Bank::insert($bank);
    }
}
