<?php

namespace App\Modules\Setting\Database\Seeders;

use App\Modules\Setting\Entities\Setting;
use App\Modules\Setting\Entities\SettingPartial;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DeleteFieldsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $first_page_category_text = Setting::where('key','first_page_category_text')->first();
        if ($first_page_category_text != null){
            $first_page_category_text->delete();
        }
        $first_page_sample_text = Setting::where('key','first_page_sample_text')->first();
        if ($first_page_sample_text != null){
            $first_page_sample_text->delete();
        }
        $work_hours_first_page_text = Setting::where('key','work_hours_first_page_text')->first();
        if ($work_hours_first_page_text != null){
            $work_hours_first_page_text->delete();
        }
        $work_hours_first_page = Setting::where('key','work_hours_first_page')->first();
        if ($work_hours_first_page != null){
            $work_hours_first_page->delete();
        }
        Setting::whereIn('key', [
            'first_page_about_image_1',
            'first_page_about_image_2',
            'first_page_about_image_3',
            'first_page_about_image_4',
            'first_page_about_image_5',
            'footer_contacts',
            'call_to_action_footer_text',
            'footer_animation',
            'first_page_first_animation',
            'slider_animation',
        ])->delete();
    }
}
