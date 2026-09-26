<?php

namespace App\Modules\Product\Entities;

use App\Modules\Comment\Entities\Comment;
use App\Modules\Faq\Entities\Faq;
use App\Modules\General\Helper\FileManager;
use App\Modules\General\Traits\GlobalScopesTrait;
use App\Modules\General\Traits\Searchable;
use App\Modules\General\Traits\UrlSetterTrait;
use App\Modules\Seo\Traits\Seoable;
use App\Modules\Tag\Entities\Tag;
use App\Modules\Product\Entities\ProductSpecification;
use App\Modules\Product\Entities\Specification;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class ProductAssociation extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    use GlobalScopesTrait;
    use Searchable;
    use Seoable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id', 'related_product_id', 'type'
    ];

}
