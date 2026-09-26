<?php

namespace App\Modules\Gallery\Database\Seeders;

use App\Modules\Gallery\Entities\Gallery;
use App\Modules\Gallery\Entities\GalleryCategory;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $gallery_cats = [
            [
                'title' => 'دسته بندی اول گالری',
                'description' => 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است ',
                'url' => 'gallery-url-1',
                'image' => null,
                'status'=>null,
                'parent_id'=>null,
                'show_in_first_page' => 1
            ],
            [
                'title' => 'دسته بندی دوم گالری',
                'description' => 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است ',
                'url' => 'gallery-url-2',
                'image' => null,
                'status'=>null,
                'parent_id'=>null,
                'show_in_first_page' => 1
            ],
            [
                'title' => 'دسته بندی سوم گالری',
                'description' => 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است ',
                'url' => 'gallery-url-3',
                'image' => null,
                'status'=>null,
                'parent_id'=>null,
                'show_in_first_page' => 1
            ],
            [
                'title' => 'دسته بندی چهارم گالری',
                'description' => 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است ',
                'url' => 'gallery-url-4',
                'image' => null,
                'show_in_first_page' => 1
            ],
            [
                'title' => 'لورم ایپسوم',
                'description' => 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است ',
                'url' => 'gallery-url-6',
                'image' => null,
                'status'=>null,
                'parent_id'=>null,
                'show_in_first_page' => 1
            ],
            [
                'title' => 'لورم ایپسوم',
                'description' => 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است ',
                'url' => 'gallery-url-6',
                'image' => null,
                'status'=>null,
                'parent_id'=>null,
                'show_in_first_page' => 1
            ],
        ];
        foreach ($gallery_cats as $gallery_cat) {
            $cat = GalleryCategory::create($gallery_cat);
            Gallery::create([
                'title'=> "{$cat->id}لورم ایپسوم",
                'description' => 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است ',
                'url'=>null,
                'file'=>null,
                'status'=>null,
                'parent_id' => $cat->id,
                'title_seo'=>null,
                'description_seo'=>null,
                'show_in_first_page'=>1,
            ]);
        }
    }
}
