<?php

namespace App\Modules\Order\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Order\Entities\Bank;

class DigipayBank extends Seeder
{
    public function run(): void
    {
        Bank::insert([
            [
                'title' => 'دیجی‌پی',
                'icon' => 'digipay.png',
                'status' => 0,
                'bank_type' => 'digipay',
                'config' => null,
            ],
        ]);
    }
}
