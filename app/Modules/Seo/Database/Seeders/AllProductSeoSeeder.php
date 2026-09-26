<?php

namespace App\Modules\Seo\Database\Seeders;

use App\Modules\Seo\Entities\SeoMeta;
use Illuminate\Database\Seeder;

class AllProductSeoSeeder extends Seeder
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
                'title_seo' =>' همه محصولات',
                'description_seo' =>' همه محصولات',
                'url' =>'/all-products',
                'noindex'=>1,
            ],
        ];
        SeoMeta::insert($seo);
    }
}
