<?php

namespace App\Modules\Tag\Entities;

use App\Modules\General\Helper\FileManager;
use App\Modules\General\Traits\GlobalScopesTrait;
use App\Modules\Seo\Traits\Seoable;
use App\Modules\Product\Entities\Product;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Taggable extends Authenticatable
{
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tag_id' , 'taggable_id' ,'taggable_type'
    ];

    public function getTaggableTypeAttribute($value)
    {
        return \App\Services\CmsCoreNamespaceConverter::normalizeClassName($value);
    }

    public function setTaggableTypeAttribute($value)
    {
        $this->attributes['taggable_type'] = \App\Services\CmsCoreNamespaceConverter::normalizeClassName($value);
    }
}
