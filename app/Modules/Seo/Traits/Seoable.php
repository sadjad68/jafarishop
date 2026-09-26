<?php

namespace App\Modules\Seo\Traits;

trait Seoable
{
    public function seo()
    {
        return $this->morphOne('App\Modules\Seo\Entities\SeoMeta', 'seoable');
    }

    public function getSeoTitleAttribute()
    {
        return $this->seo ? $this->seo->title_seo : null;
    }
    public function getSeoH1Attribute()
    {
        return $this->seo ? $this->seo->h1 : null;
    }

    public function getSeoDescriptionAttribute()
    {
        return $this->seo ? $this->seo->description_seo : null;
    }

    public function getSeoIndexAttribute()
    {
        return $this->seo ? $this->seo->noindex : 0;
    }

    public function getH1PagesAttribute($attribute)
    {
        return $this->seo->h1 ? $this->seo->h1 : $attribute->title;
    }

}
