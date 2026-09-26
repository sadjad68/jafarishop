<?php

namespace App\Modules\Setting\Database\Seeders;

use App\Modules\Setting\Entities\Setting;
use App\Modules\Setting\Entities\SettingPartial;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class SettingSmsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        $partial = SettingPartial::create([
//            'title' => 'تنظیمات پیامک',
//            'parent_id' => null,
//            'show' => 1,
//            'sort' => 2,
//        ]);
//
//        $setting = [
//            [
//                'key' => 'kavenegar_key',
//                'value' => null,
//                'type' => 'text',
//                'sort' => 1,
//                'group' => null,
//                'section' => null,
//                'class' => null,
//                'p_name' => 'شناسه کاوه نگار',
//                'partial_id' => $partial->id,
//                'options' => null,
//            ],
//            [
//                'key' => 'admin_mobile',
//                'value' => null,
//                'type' => 'text',
//                'sort' => 2,
//                'group' => null,
//                'section' => null,
//                'class' => null,
//                'p_name' => 'تلفن ادمین برای ارسال پیامک',
//                'partial_id' => $partial->id,
//                'options' => null,
//            ],
//        ];
//        Setting::insert($setting);
    }
}
