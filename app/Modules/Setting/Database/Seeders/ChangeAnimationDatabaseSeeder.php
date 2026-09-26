<?php

namespace App\Modules\Setting\Database\Seeders;

use App\Modules\Setting\Entities\Setting;
use Illuminate\Database\Seeder;

class ChangeAnimationDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::where('key', 'slider_animation')->delete();
    }
}
