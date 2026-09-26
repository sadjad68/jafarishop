<?php

namespace App\Console;

use App\Library\SiteHelper;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use App\Modules\Product\Services\ProductService;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\MigrateMultipleDatabases::class,
        \App\Console\Commands\CreateDatabaseCommand::class,
        \App\Console\Commands\SetConfigSites::class,
        \App\Console\Commands\SeedMultipleDatabases::class,
        \App\Console\Commands\ExpireStockReservations::class,
        \App\Console\Commands\InquireZarinpalPayments::class,
        \App\Console\Commands\ConvertCmsCoreNamespaces::class,
        \App\Console\Commands\ImportTopickalaLegacy::class,
    ];
    /**
     * Define the application's command schedule.
     *
     * Shared hosts often disable proc_open; run scheduled work via
     * Artisan::call() in-process instead of spawning Process children.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            Artisan::call('order:expire-stock-reservations');
        })->everyMinute()->name('expire-stock-reservations');

        $schedule->call(function () {
            Artisan::call('order:inquire-zarinpal-payments');
        })->everyMinute()->name('inquire-zarinpal-payments')->withoutOverlapping();

        $schedule->call(function () {
            Artisan::call('queue:work', [
                'connection' => 'database',
                '--stop-when-empty' => true,
                '--max-time' => 50,
                '--tries' => 3,
            ]);
        })->everyMinute()->name('queue-work')->withoutOverlapping();

        $schedule->call(function () {
            $site = SiteHelper::getInformation();
            if (($site['site_name'] ?? null) !== 'khodadadgallery') {
                return;
            }

            $products = ProductService::findAll(['price_formula' => true]);
            foreach ($products as $product) {
                try {
                    $priceFormula = $product->price_formula;
                    $class = 'App\\Modules\\Product\\Library\\' . $priceFormula;
                    if (count($product->variants) == 0) {
                        $response = new $class($product->weight);
                        $price = $response->fetchData();
                        $product->update([
                            'price' => $price,
                            'discounted_price' => 0,
                            'final_price' => $price,
                        ]);
                    } else {
                        foreach ($product->variants as $variant) {
                            $response = new $class($variant->weight);
                            $price = $response->fetchData();
                            $variant->update([
                                'price' => $price,
                                'discounted_price' => 0,
                                'final_price' => $price,
                            ]);
                        }
                        $sum_stock = $product->variants()->orderBy('final_price', 'ASC')->sum('stock');
                        $minimum_price_variant = $product->variants()
                            ->orderByRaw('CAST(final_price AS UNSIGNED) ASC')
                            ->where('stock', '<>', '0')->where('final_price', '<>', '0')
                            ->first();
                        $product->update([
                            'price' => $minimum_price_variant ? $minimum_price_variant['price'] : 0,
                            'discounted_price' => $minimum_price_variant ? $minimum_price_variant['discounted_price'] : 0,
                            'final_price' => $minimum_price_variant ? $minimum_price_variant['final_price'] : 0,
                            'stock' => $sum_stock,
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::info('خطا در دریافت اطلاعات: ' . $e->getMessage());
                }
            }
        })->everyTenMinutes()->name('khodadadgallery-price-formula');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
