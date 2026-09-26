<?php

namespace App\Modules\General\Services;

use App\Modules\Seo\Entities\SeoMeta;

class ApiSeoService
{

    public static function set($item,$data){
        $data['seoable_id'] = $item->id;
        $data['seoable_type'] = $item::class;

        $seo = SeoMeta::where([
            'seoable_id' => $data['seoable_id'],
            'seoable_type' => $data['seoable_type'],
        ])->first();

        if (!$seo) {
            $seo = SeoMeta::create($data);
        }else{
            $seo->update($data);
        }
    }
}
