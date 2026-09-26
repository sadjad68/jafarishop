<?php

namespace App\Modules\Service\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Blog\Entities\Blog;
use App\Modules\Blog\Entities\BlogCategory;
use App\Modules\Service\Entities\Package;
use App\Modules\Service\Entities\Service;
use App\Modules\Service\Entities\WorkSample;

class ServicePackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $numCollections = 3;
        $numLevels = 6;
        $numWorkSamples = 6;
        $numPackages = 3;
        $numBlogCategories = 4;
        $numBlogs = 8;

        $packages = [];
        for ($i = 1; $i <= $numPackages; $i++) {
            $packages[] = Package::create([
                'title' => "پکیج {$i}",
                'url' => "url-{$i}",
                'title_seo' => " {$i}عنوان سئو ",
                'description' => "لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است {$i}",
                'description_seo' => "لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است {$i}",
                'price' => 50000000,
                'discounted_price' => 4500000,
                'status' => null,
                'image' => null,
                'items' => null,
                'show_in_first_page' => 1,
            ]);
        }

        $workSamples = [];
        for ($i = 1; $i <= $numWorkSamples; $i++) {
            $workSamples[] = WorkSample::create([
                'title' => "نمونه کار {$i}",
                'description' => "توضیحات برای خدمت مجمو لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است ",
                'double_image' => false,
                'show_in_first_page' => 1,
                'has_page' => 1,
                'url' => "work-sample-url{$i}",
                'short_description' => "توضیحات برای خدمت مجمو لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است ",
            ]);
        }

        $lastServices = [];

        for ($i = 1; $i <= $numCollections; $i++) {
            $parent_id = null;
            for ($level = 1; $level <= $numLevels; $level++) {
                $service = Service::create([
                    'title' => "خدمت سطح{$level}",
                    'description' => "توضیحات برای خدمت مجمو لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است ",
                    'url' => "url-{$i}-level-{$level}",
                    'status' => 1,
                    'parent_id' => $parent_id,
                    'image' => null,
                    'show_in_first_page' => true,
                    'show_in_menu' => false,
                    'show_in_footer' => false,
                    'header_image' => null,
                    'phone_number' => "123-456-789{$level}",
                    'short_description' => "لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است ",
                ]);

                if ($level == $numLevels) {
                    $lastServices[] = $service;
                }

                $parent_id = $service->id;
            }
        }
        $footerServices = $lastServices;
        shuffle($footerServices);
        $selectedFooterServices = array_slice($footerServices, 0, 6);
        foreach ($selectedFooterServices as $footerService) {
            $footerService->update([
            'show_in_footer' => true,
            'show_in_menu'=> true]);
        }

        $newsCategories = [];
        $news = BlogCategory::create([
            'title' => "اخبار",
            'description' => "لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و برای شرایط فعلی تکنولوژی مورد نیاز ",
            'url' => "news",
            'image' => null,
            'parent_id' => null,
        ]);
        for ($i = 1; $i <= $numBlogCategories; $i++) {

            $newsCategories[] = BlogCategory::create([
                'title' => "دسته‌بندی خبر{$i}",
                'description' => "لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و برای شرایط فعلی تکنولوژی مورد نیاز ",
                'url' => "news-category-url-{$i}",
                'image' => null,
                'parent_id' => $news['id'],
            ]);
        }
        $postCategories = [];
        $posts = BlogCategory::create([
            'title' => "مطالب",
            'description' => "لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و برای شرایط فعلی تکنولوژی مورد نیاز ",
            'url' => "blogs",
            'image' => null,
            'parent_id' => null,
        ]);
        for ($i = 1; $i <= $numBlogCategories; $i++) {

            $postCategories[] = BlogCategory::create([
                'title' => "دسته‌بندی مطلب{$i}",
                'description' => "لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و برای شرایط فعلی تکنولوژی مورد نیاز ",
                'url' => "blog-category-url-{$i}",
                'image' => null,
                'parent_id' => $posts['id'],
            ]);
        }
        $blogCategories = array_merge($newsCategories, $postCategories);
        $blogs = [];
        foreach ($blogCategories as $category) {
            for ($i = 1; $i <= 2; $i++) {
                $blogs[] = Blog::create([
                    'title' => "پست {$category->id}-{$i}",
                    'description' => "لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و برای شرایط فعلی تکنولوژی مورد نیاز ",
                    'url' => "post-url-{$category->id}-{$i}",
                    'parent_id' => $category->id,
                    'image' => null,
                    'author' => "نویسنده {$category->id}-{$i}",
                    'call_to_action' => $i % 2 == 0,
                    'publish_date' => now(),
                    'view' => rand(100, 1000),
                ]);
            }
        }

        foreach ($lastServices as $service) {
            foreach ($packages as $package) {
                $service->packages()->attach($package->id);
            }
        }

        foreach ($lastServices as $service) {
            $randomWorkSamples = $workSamples;
            shuffle($randomWorkSamples);
            $selectedWorkSamples = array_slice($randomWorkSamples, 0, 3);
            foreach ($selectedWorkSamples as $workSample) {
                $service->samples()->attach($workSample->id);
            }
        }

        foreach ($blogs as $blog) {
            $randomServices = $lastServices;
            shuffle($randomServices);
            $selectedServices = array_slice($randomServices, 0, 2);
            foreach ($selectedServices as $service) {
                $service->blogs()->attach($blog->id);
            }
        }
    }
}
