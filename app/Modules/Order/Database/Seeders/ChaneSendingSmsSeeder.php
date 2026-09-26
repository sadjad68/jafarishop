<?php

namespace App\Modules\Order\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Order\Entities\OrderShippingStatus;

class ChaneSendingSmsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses  = OrderShippingStatus::all();
        foreach($statuses as $row){
            $row->sending_sms = 1;
            $row->save();
        }
    }
}
