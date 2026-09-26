<?php

namespace App\Modules\Product\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Product\Entities\Product;
use App\Modules\Product\Entities\ProductCategory;
use App\Modules\Product\Entities\Brand;
use App\Modules\Product\Entities\Specification;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $numMainCategories = 3;
        $numSubCategories = 1;
        $numSubSubCategories = 1;
        $numProducts = 8;
        $numBrands = 6;
//        $numSpecifications = 15;

        $brands = [];
        for ($i = 1; $i <= $numBrands; $i++) {
            $brands[] = Brand::create([
                'title' => "برند {$i}",
                'description' => "توضیحات برای خدمت مجمو لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است ",
                'url' => "brand-url-{$i}",
                'active' => true,
                'image' => null,
            ]);
        }
        $products = [];
        for ($i = 1; $i <= $numProducts; $i++) {
            $products[] = Product::create([
                'title' => "محصول {$i}",
                'description' => "توضیحات برای خدمت مجمو لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است ",
                'url' => "product-url-{$i}",
                'brand_id' => $brands[array_rand($brands)]->id,
                'active' => true,
                'image' => null,
                'price' => 500000,
                'discounted_price' => 80000,
                'final_price' => 80000,
                'show_in_first_page' => true,
                'timer_active' => false,
                'end_timer' => null,
                'start_timer' => null,
            ]);
        }

        $subSubCategories = [];
        for ($i = 1; $i <= $numMainCategories; $i++) {
            $mainCategory = ProductCategory::create([
                'title' => "لورم ایپسوم {$i}",
                'description' => "توضیحات برای خدمت مجمو لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است ",
                'url' => "main-category-url-{$i}",
                'active' => true,
                'parent_id' => null,
                'image' => null,
                'show_in_first_page' => true
            ]);

            for ($j = 1; $j <= $numSubCategories; $j++) {
                $subCategory = ProductCategory::create([
                    'title' => "لورم ایپسوم {$i}-{$j}",
                    'description' => "توضیحات برای خدمت مجمو لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است ",
                    'url' => "sub-category-url-{$i}-{$j}",
                    'active' => true,
                    'parent_id' => $mainCategory->id,
                    'image' => null,
                    'show_in_first_page' => true
                ]);
                for ($k = 1; $k <= $numSubSubCategories; $k++) {
                    $subSubCategory = ProductCategory::create([
                        'title' => "لورم ایپسوم{$i}-{$j}-{$k}",
                        'description' => "توضیحات برای خدمت مجمو لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است ",
                        'url' => "sub-sub-category-url-{$i}-{$j}-{$k}",
                        'active' => true,
                        'parent_id' => $subCategory->id,
                        'image' => null,
                        'show_in_first_page' => true
                    ]);
                    $subSubCategories[] = $subSubCategory;
                }
            }
        }
        $categoryIndex = 0;
        foreach ($products as $product) {
            $subSubCategories[$categoryIndex]->products()->attach($product->id);
            $categoryIndex = ($categoryIndex + 1) % count($subSubCategories);
        }
//        $specifications = [];
//        for ($i = 1; $i <= $numSpecifications; $i++) {
//            $isColor = $i % 2 === 0;
//            $specifications[] = Specification::create([
//                'title' => "مشخصه {$i}",
//                'type' => $i % 2 === 0 ? 'select' : 'text',
//                'active' => true,
//                'is_filter' => $i % 2 === 0,
//                'is_color' => $isColor,
//                'color_code' => $isColor ? sprintf('#%06X', mt_rand(0, 0xFFFFFF)) : null,
//            ]);
//        }
//        foreach ($subSubCategories as $category) {
//            $randomSpecifications = $specifications;
//            shuffle($randomSpecifications);
//            $selectedSpecifications = array_slice($randomSpecifications, 0, 2);
//            foreach ($selectedSpecifications as $specification) {
//                $category->specifications()->attach($specification->id);
//            }
//        }
//        foreach ($products as $product) {
//            $randomSpecifications = $specifications;
//            shuffle($randomSpecifications);
//            $selectedSpecifications = array_slice($randomSpecifications, 0, 2);
//            foreach ($selectedSpecifications as $specification) {
//                $product->specifications()->attach($specification->id);
//            }
//        }
    }
}
