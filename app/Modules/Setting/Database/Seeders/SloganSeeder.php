<?php

namespace App\Modules\Setting\Database\Seeders;

use App\Modules\Setting\Entities\Slogan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class SloganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $slogan = [
            [
                'value' => 'ضمانت سلامت کالا',
                'icon' => null,
                'active' => 1,
            ],
            [
                'value' => 'تضمین سلامت فیزیکی',
                'icon' => null,
                'active' => 1,
            ],
            [
                'value' => 'کیفیت برتر، انتخاب بهتر',
                'icon' => null,
                'active' => 1,
            ],
            [
                'value' => 'بهترین‌ها برای شما',
                'icon' => null,
                'active' => 1,
            ],
        ];
        Slogan::insert($slogan);
    }
}
