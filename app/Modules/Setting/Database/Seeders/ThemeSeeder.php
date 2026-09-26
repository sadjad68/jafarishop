<?php

namespace App\Modules\Setting\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Setting\Entities\Theme;

class ThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Theme::where('key','menu_type')->delete();
        Theme::where('key','menu_type_category')->delete();
        Theme::where('key','color_type')->delete();
        Theme::insert([
            [
                'key' => 'menu_type',
                'value' => 'drop_down',
                'type' => 'select_box',
                'p_name' => 'نوع منوی خدمات'
            ],
            [
                'key' => 'menu_type_category',
                'value' => 'drop_down',
                'type' => 'select_box',
                'p_name' => 'نوع منوی دسته بندی'
            ],
            [
                'key' => 'color_type',
                'value' => '{"color-one":"#E7E0F2","color-two":"#FFC093","color-body":"#FFF7F4","text-primary" : "#1a1a1a","text-secondary":"#000","bg-table" : "rgb(231 224 242 / 62%)"}',
                'type' => 'select_box_color',
                'p_name' => 'ترکیب رنگی '
            ],
        ]);
    }
}
