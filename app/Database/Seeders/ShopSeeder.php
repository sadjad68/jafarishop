<?php

namespace App\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Modules\General\Helper\ModuleUtils;
use App\Modules\Location\Database\Seeders\StateSeeder;
use App\Modules\Order\Database\Seeders\BankSeeder;
use App\Modules\Order\Database\Seeders\OrderShippingStatusSeeder;
use App\Modules\Setting\Database\Seeders\SettingsDatabaseSeeder;
use App\Modules\Setting\Database\Seeders\SettingSmsSeeder;
use App\Modules\Setting\Database\Seeders\ThemeSeeder;
use App\Modules\User\Database\Seeders\RoleDatabaseSeeder;

class ShopSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(BankSeeder::class);
        $this->call(StateSeeder::class);
        $this->call(OrderShippingStatusSeeder::class);
        $this->call(SettingsDatabaseSeeder::class);
        $this->call(RoleDatabaseSeeder::class);
    }
}
