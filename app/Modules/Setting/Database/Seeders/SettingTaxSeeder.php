<?php

namespace App\Modules\Setting\Database\Seeders;

use App\Modules\Setting\Entities\Setting;
use App\Modules\Setting\Entities\SettingPartial;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class SettingTaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $setting = [
            [
                'key' => 'tax',
                'value' => 0,
                'type' => 'text',
                'sort' => 1,
                'group' => null,
                'section' => null,
                'class' => null,
                'p_name' => 'مالیات بر ارزش افزوده',
                'partial_id' => null,
                'options' => null,
            ],

        ];
        Setting::insert($setting);
    }
}
