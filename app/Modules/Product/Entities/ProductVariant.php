<?php

namespace App\Modules\Product\Entities;

use Illuminate\Support\Facades\DB;
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

class ProductVariant extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    use GlobalScopesTrait;
    use Searchable;
    use Seoable;
    protected $appends = ['percent'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
       'product_id',
        'price', 'discounted_price', 'final_price', 'price_affective','stock',
        'specification_parent_id','specification_id','weight','price_balancing'
    ];

    public function specification()
    {
        return $this->hasOne(Specification::class, 'id', 'specification_id');
    }
    public function specifications()
    {
        return $this->belongsToMany(Specification::class, 'product_variant_specification', 'product_variant_id', 'specification_value_id')
            ->with('parent');
    }
    public function images()
    {
        return $this->belongsToMany(Image::class, 'image_product_variant', 'product_variant_id', 'image_id')->orderBy('thumbnail','DESC');
    }


    public function calculateDiscount($originalPrice, $discountedPrice)
    {

    }
//    public function images()
//    {
//        return $this->hasMany(Image::class, 'product_id', 'product_id')
//            ->leftJoin('specifications', 'images.specification_id', '=', 'specifications.id')
//            ->where(function ($query) {
//                $query->where('images.specification_id', $this->attributes['specification_id'])
//                    ->orWhereNull('images.specification_id');
//            })
//            ->select('images.*'); // انتخاب تمام فیلدهای تصویر
//    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }




    public function getPercentAttribute()
    {
        $originalPrice = $this->attributes['price'] ?? null;
        $discountedPrice = $this->attributes['final_price'] ?? null;

        if (!$originalPrice || !$discountedPrice || intval($this->attributes['discounted_price'] ?? 0) === 0) {
            // بررسی اینکه قیمت اولیه نباید صفر یا منفی باشد
            if ($originalPrice <= 0) {
                return 0;
            }
            // محاسبه مقدار تخفیف
            $discountAmount = $originalPrice - $discountedPrice;
            // محاسبه درصد تخفیف
            return round(($discountAmount / $originalPrice) * 100);
        } else {
            return null;
        }
    }

    public function getVariantTitleAttribute()
    {
        $specifications = $this->relationLoaded('specifications')
            ? $this->specifications
            : $this->specifications()->get();

        if ($specifications->isNotEmpty()) {
            return $specifications->pluck('title')->implode(' - ');
        }

        $singleSpecification = $this->relationLoaded('specification')
            ? $this->specification
            : $this->specification()->first();

        if ($singleSpecification) {
            return $singleSpecification->title;
        }

        return 'بدون عنوان';
    }

}

