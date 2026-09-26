<?php

namespace App\Modules\Seo\Database\Seeders;

use App\Modules\Seo\Entities\SeoMeta;
use Illuminate\Database\Seeder;

class SeoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seo = [
            [
                'seoable_type' => null,
                'seoable_id' => null,
                'title_seo' =>'صفحه اول',
                'description_seo' =>'صفحه اول',
                'url' =>'/',
                'noindex'=>1,
            ],
            [
                'seoable_type' => null,
                'seoable_id' => null,
                'title_seo' =>'درباره ما',
                'description_seo' =>'درباره ما',
                'url' =>'/about-us',
                'noindex'=>1,
            ],
            [
                'seoable_type' => null,
                'seoable_id' => null,
                'title_seo' =>'تماس باما',
                'description_seo' =>'تماس باما',
                'url' =>'/contact-us',
                'noindex'=>1,
            ],
            [
                'seoable_type' => null,
                'seoable_id' => null,
                'title_seo' =>'گالری تصاویر',
                'description_seo' =>'گالری',
                'url' =>'/galleries',
                'noindex'=>1,
            ],
            [
                'seoable_type' => null,
                'seoable_id' => null,
                'title_seo' =>'نمونه کار ها',
                'description_seo' =>'نمونه کارها',
                'url' =>'/portfolios',
                'noindex'=>1,
            ],
            [
                'seoable_type' => null,
                'seoable_id' => null,
                'title_seo' =>'خدمات',
                'description_seo' =>'خدمات',
                'url' =>'/services',
                'noindex'=>1,
            ],
            [
                'seoable_type' => null,
                'seoable_id' => null,
                'title_seo' =>'پکیج',
                'description_seo' =>'پکیج',
                'url' =>'/packages',
                'noindex'=>1,
            ],
            [
                'seoable_type' => null,
                'seoable_id' => null,
                'title_seo' =>'دسته بندی محصولات',
                'description_seo' =>'دسته بندی محصولات',
                'url' =>'/categories',
                'noindex'=>1,
            ],
        ];
        SeoMeta::insert($seo);
    }
}
