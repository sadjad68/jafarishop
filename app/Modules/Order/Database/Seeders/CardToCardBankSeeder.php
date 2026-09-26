<?php

namespace App\Modules\Order\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Order\Entities\Bank;

class CardToCardBankSeeder extends Seeder
{
    public function run(): void
    {
        if (Bank::where('bank_type', 'cardtocard')->exists()) {
            return;
        }

        Bank::insert([
            [
                'title' => 'کارت به کارت',
                'icon' => 'cardtocard.png',
                'status' => 0,
                'bank_type' => 'cardtocard',
                'config' => json_encode([
                    'card_number' => null,
                    'shaba_number' => null,
                    'account_holder_name' => null,
                ]),
            ],
        ]);
    }
}
