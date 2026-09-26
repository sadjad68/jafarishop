<?php

namespace App\Modules\Seo\Http\Resources;

use App\Modules\General\Helper\FileManager;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SeoCollection extends ResourceCollection
{
    public function toArray($request): array
    {

        $seo_statics =  null;
        $seo_statics = SeoMeta::orderByDesc('id')->whereNotNull('url')->where('url','/'.implode('/',request()->segments()))->first(['title_seo','description_seo','description','noindex']);


        $themes = [];
        foreach ($this->collection as $row) {
            $themes[$row->key] = match ($row->type) {
                default => $row->value,
            };
        }
        return $themes;
    }

}
