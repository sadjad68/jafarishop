<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\Order\Services\OrderService;

class ExpireStockReservations extends Command
{
    protected $signature = 'order:expire-stock-reservations';
    protected $description = 'Expire card-to-card stock reservations that have a due expires_at, restore product stock, and keep payment status unchanged';

    public function handle()
    {
        $count = OrderService::expireStockReservations();
        $this->info("Expired stock reservations for {$count} order(s).");

        return self::SUCCESS;
    }
}
