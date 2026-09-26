<?php

namespace App\Modules\Seo\Database\Seeders;

use App\Modules\Seo\Entities\SeoMeta;
use Illuminate\Database\Seeder;

class NewSeoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seo_delete = SeoMeta::where('url','/blogs')->first();
        if(@$seo_delete){
            $seo_delete->delete();
        }
        $seo = [
            [
                'seoable_type' => null,
                'seoable_id' => null,
                'title_seo' =>' دسته بندی مطالب',
                'description_seo' =>' دسته بندی مطالب',
                'url' =>'/posts',
                'noindex'=>1,
            ],
            [
                'seoable_type' => null,
                'seoable_id' => null,
                'title_seo' =>'برند ',
                'description_seo' =>'برند ',
                'url' =>'/brands',
                'noindex'=>1,
            ],
            [
                'seoable_type' => null,
                'seoable_id' => null,
                'title_seo' =>'تخفیفات',
                'description_seo' =>'تخفیفات',
                'url' =>'/discounted-list',
                'noindex'=>1,
            ],
            [
                'seoable_type' => null,
                'seoable_id' => null,
                'title_seo' =>'تگ ها',
                'description_seo' =>'تگ ها',
                'url' =>'/tags',
                'noindex'=>1,
            ],
        ];
        SeoMeta::insert($seo);
    }
}
