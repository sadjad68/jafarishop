<?php

namespace App\Modules\Banner\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Banner\Entities\Banner;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Banner::create([
            'show_in_first_page' => true,
            'title' => 'First Banner',
            'image' => null,
            'image_mobile' => null
        ]);

        Banner::create([
            'show_in_first_page' => true,
            'title' => 'Second Banner',
            'image' => null,
            'image_mobile' => null
        ]);
    }
}
