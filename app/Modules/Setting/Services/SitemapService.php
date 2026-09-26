<?php

namespace App\Modules\Setting\Services;

use Illuminate\Support\Facades\Redirect;
use App\Modules\General\Helper\FileManager;
use App\Modules\General\Helper\NumberHelper;
use App\Modules\General\Helper\TestImage;
use App\Modules\Setting\DTO\SitemapDTO;
use App\Modules\Setting\DTO\SloganDTO;
use App\Modules\Setting\Entities\Setting;
use App\Modules\Setting\Entities\Sitemap;
use App\Modules\Setting\Entities\Slogan;


class SitemapService
{
    public function update($request)
    {
        $sitemap = $request->except('_token');
        foreach ($sitemap as $key => $array) {
            $main_key = str_replace('_', '.', $key);
            $sitemapItem = Sitemap::where('key', $main_key)->first();
                $sitemapItem->update([
                    'priority' => $array['priority'],
                    'change_frequency' => $array['change_frequency'],
                ]);

        }
    }


    public static function findAll($query = [])
    {
        $sitemaps = Sitemap::query();
        if (isset($query['show'])) {
            $sitemaps->where('show',$query['show']);
        }
        $sitemaps = $sitemaps->orderby('id', 'ASC')->get();
        return $sitemaps;

    }
}
