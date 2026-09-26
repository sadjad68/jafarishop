<?php

namespace App\Modules\Setting\Database\Seeders;

use Illuminate\Support\Facades\Config;
use App\Modules\Setting\Entities\Setting;
use Illuminate\Database\Seeder;
use App\Modules\Setting\Entities\Sitemap;

class SitemapDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sitemaps = Config::get('sitemap_structure.sitemap');
        foreach ($sitemaps as $key => $array) {
            $main_key = str_replace('_', '.', $array['key']);
            $sitemapItem = Sitemap::where('key', $main_key)->first();
            if (!$sitemapItem){
                Sitemap::create([
                    'key' => $array['key'],
                    'p_name' => $array['p_name'],
                    'change_frequency' => $array['change_frequency'],
                    'priority' => $array['priority'],
                    'show' => $array['show'],
                ]);
            }

        }
    }

}

