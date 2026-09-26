<?php

namespace App\Modules\Seo\Database\Seeders;

use App\Modules\Seo\Entities\SeoMeta;
use Illuminate\Database\Seeder;
use App\Modules\Setting\Entities\Setting;

class FirstPageSeoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seo_first = SeoMeta::where('url','/')->first();
        $setting_slider = Setting::where('key','slider_title')->first();
        $setting_title = Setting::where('key','siteName_fa')->first();
        if(strlen(@$seo_first['h1']) ==  0 && $setting_slider != null){
            $seo_first->update([

               'h1'=> $setting_slider['value'] != null ? $setting_slider['value'] : $setting_title['value']
            ]);
        }

    }
}
