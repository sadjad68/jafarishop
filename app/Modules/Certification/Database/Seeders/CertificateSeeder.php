<?php

namespace App\Modules\Certification\Database\Seeders;

use App\Modules\Certification\Entities\Certificate;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Banner\Entities\Banner;

class CertificateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Certificate::create([
            'title' => 'Certificate 1',
            'description' => 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است ',
            'image' => null,
            'show_in_first_page' => 1,
        ]);
        Certificate::create([
            'title' => 'Certificate 2',
            'description' => 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است ',
            'image' => null,
            'show_in_first_page' => 1,
        ]);
        Certificate::create([
            'title' => 'Certificate 3',
            'description' => 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است ',
            'image' => null,
            'show_in_first_page' => 1,
        ]);
        Certificate::create([
            'title' => 'Certificate 4',
            'description' => 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است ',
            'image' => null,
            'show_in_first_page' => 1,
        ]);
    }
}
