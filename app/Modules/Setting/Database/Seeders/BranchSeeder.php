<?php

namespace App\Modules\Setting\Database\Seeders;

use App\Modules\Setting\Entities\Branch;
use App\Modules\Setting\Entities\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $branch = [
            [
                'title' => 'شعبه VIP',
                'address'=>'انتهای پاسداران،تنگستان چهارم،مجتمع حیاط سبز،طبقه همکف اول،شماره 6 و 7',
                'map'=>'https://maps.app.goo.gl/fc6jZuR5qRGS1FZa6',
                'main'=>1,
            ],
            [
                'title'=>'دفتر مرکزی',
                'address'=>'ملاصدرا، شیراز جنوبی، برزیل غربی، شماره ۵۹، واحد ۹',
                'map'=>'https://maps.app.goo.gl/ag2r9ifqpSsLznoQ8',
                'main'=>0,
            ],
            [
                'title'=>'دفتر اصلی',
                'address'=>'ملاصدرا، شیراز جنوبی، برزیل غربی، شماره ۵۹، واحد ۹',
                'map'=>'https://maps.app.goo.gl/ag2r9ifqpSsLznoQ8',
                'main'=>1,
            ],
        ];

        Branch::insert($branch);

    }
}
