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
use App\Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Builder;

class Product extends Authenticatable
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
        'title', 'description', 'url', 'old_id', 'brand_id', 'active', 'image',
//        'unstable_price',
        'price', 'discounted_price', 'final_price', 'show_in_first_page', 'timer_active', 'end_timer', 'start_timer', 'stock',
        'main_variant_specification_id', 'weight', 'price_formula', 'creator_id',
    ];

    public function getImage($size = "medium")
    {
        return FileManager::serveFile(
            'uploads/product/' . $size . '/' . $this->attributes['image'], 'assets/notfounds/product-img.jpg'
        );
    }

    public function brand()
    {
        return $this->hasOne(Brand::class, 'id', 'brand_id');
    }

    public function getDateAttribute()
    {
        $sourceDate = $this->updated_at ?? $this->created_at;
        return $sourceDate ? jdate('d F Y', $sourceDate) : '';
    }

    public function calculateDiscount($originalPrice, $discountedPrice)
    {

    }

    public function getPercentAttribute()
    {
        if (intval($this->attributes['stock']) != 0) {
            $originalPrice = $this->attributes['price'];
            $discountedPrice = $this->attributes['final_price'];
            if (intval($this->attributes['discounted_price']) != 0) {
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
        } else {
            return null;
        }

    }

    public function categories()
    {
        return $this->belongsToMany(ProductCategory::class, 'product_category_product', 'product_id');
    }

    public function main_specifications()
    {
        return $this->belongsToMany(Specification::class, 'product_main_specifications', 'product_id', 'main_specification_id');
    }

    public function category()
    {
        return $this->belongsToMany(ProductCategory::class, 'product_category_product', 'product_id')->select('id');
    }
    public function categoryForStoryab()
    {
        return $this->belongsToMany(
            ProductCategory::class,
            'product_category_product',
            'product_id',
            'product_category_id'
        )->select('product_categories.id', 'product_categories.title');
    }

    public function related()
    {
        return $this->belongsToMany(Product::class, 'product_associations', 'product_id', 'related_product_id')
            ->withPivot('type')
            ->wherePivot('type', 'related');
    }


    public function complement()
    {
        return $this->belongsToMany(Product::class, 'product_associations', 'product_id', 'related_product_id')
            ->withPivot('type')
            ->wherePivot('type', 'complement');
    }

    public function relatedIds()
    {
        return $this->belongsToMany(Product::class, 'product_associations', 'product_id', 'related_product_id')
            ->withPivot('type')
            ->wherePivot('type', 'related')
            ->select('products.id');
    }


    public function complementIds()
    {
        return $this->belongsToMany(Product::class, 'product_associations', 'product_id', 'related_product_id')
            ->withPivot('type')
            ->wherePivot('type', 'complement')
            ->select('products.id');
    }

    public function specifications()
    {
        return $this->belongsToMany(Specification::class, 'product_specification')->whereNull('product_specification.value')
            ->whereNull('product_specification.deleted_at');
    }

    public function mainVariantSpecification()
    {
        return $this->hasOne(Specification::class, 'id', 'main_variant_specification_id');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }

    public function specification_values()
    {
        return $this->belongsToMany(Specification::class, 'product_specification')->whereNull('product_specification.value')
            ->whereNull('product_specification.deleted_at')->whereHas('parent')->with('parent');
    }

    public function specification_active_values()
    {
        return $this->belongsToMany(Specification::class, 'product_specification')
//            ->where('specifications.active', 1)

            ->whereNull('product_specification.value')
            ->whereNull('product_specification.deleted_at')
            ->whereHas('parent', function ($query) {
                $query->where('active', 1);
            })
            ->with(['parent' => function ($query) {
                $query->where('active', 1);
            }]);

    }

    public function specification_value()
    {
        return $this->belongsToMany(Specification::class, 'product_specification')->whereNotNull('product_specification.value')
            ->whereNull('product_specification.deleted_at')->whereHas('parent');
    }

    public function specification_vals()
    {
        return $this->hasMany(ProductSpecification::class)->whereNotNull('product_specification.value');
    }

    public function specification_active_vals()
    {
        return $this->hasMany(ProductSpecification::class)
            ->whereNotNull('product_specification.value')
            ->whereNull('product_specification.deleted_at')
            ->whereHas('specification', function ($query) {
                $query->where('active', 1);
            });
    }

    public function comments()

    {
        return $this->morphMany(Comment::class, 'commentable')->whereNull('reply_id')->whereStatus(1)->orderBy('created_at', 'desc');
    }

    public function assignCategory($cat)
    {

        return $this->categories()->sync($cat);
    }

    public function getActiveNameAttribute()
    {
        return $this->attributes['active'] == 1 ? ['title' => 'نمایش ', 'badge' => 'success'] : ['title' => 'عدم نمایش ', 'badge' => 'danger'];

    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function faqs()
    {
        return $this->morphMany(Faq::class, 'faqable');
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'product_id')->orderBy('thumbnail', 'DESC')->orderBy('id', 'ASC');
    }

    public function properties()
    {
        return $this->hasMany(Property::class, 'product_id');
    }

    public function videos()
    {
        return $this->hasMany(Video::class, 'product_id');
    }

    public function getFirstPageNameAttribute()
    {
        return $this->attributes['show_in_first_page'] == 1 ? ['title' => 'نمایش در صفحه اول', 'badge' => 'success'] : ['title' => 'عدم نمایش در صفحه اول', 'badge' => 'danger'];
    }

    public function getStockProductAttribute()
    {
        return $this->attributes['stock'] == 0 ? ['title' => 'ناموجود', 'badge' => 'danger'] : ['title' => 'موجودی: ' . $this->attributes['stock'], 'badge' => 'primary'];
    }

    public function getTimerActiveNameAttribute()
    {
        return $this->attributes['timer_active'] == 1 ? ['title' => 'تایمر فعال', 'badge' => 'success'] : ['title' => 'تایمر غیر فعال', 'badge' => 'danger'];
    }

    public function getTimerStatusNameAttribute()
    {
        return $this->attributes['end_timer'] != null ? ['title' => jdate('d F Y H:i', $this->end_timer->timestamp), 'badge' => 'success'] : ['title' => 'بدون تایمر', 'badge' => 'danger'];
    }

    public function hasVariants(): bool
    {
        if (array_key_exists('variants_count', $this->attributes)) {
            return (int) $this->attributes['variants_count'] > 0;
        }

        if ($this->relationLoaded('variants')) {
            return $this->variants->isNotEmpty();
        }

        return ! empty($this->main_variant_specification_id);
    }

    public function scopeActive($query)
    {
        return $query->whereActive('1');
    }

    /**
     * جستجو در عنوان یا slug (url) محصول — هر کلمه باید در یکی از این دو فیلد باشد.
     */
    public function scopeWhereSearch(Builder $query, $keyword)
    {
        $keywords = explode(' ', strtolower(trim($keyword)));

        return $query->where(function ($q) use ($keywords) {
            foreach ($keywords as $word) {
                $url_word = str_replace(['-', ' ', '_'], '', $word);
                $q->where(function ($inner) use ($word, $url_word) {
                    $inner->whereRaw(
                        "REPLACE(REPLACE(LOWER(title), ' ', ''), '_', '') LIKE ?",
                        ["%{$word}%"]
                    )->orWhereRaw(
                        "REPLACE(REPLACE(REPLACE(LOWER(url), '-', ''), ' ', ''), '_', '') LIKE ?",
                        ["%{$url_word}%"]
                    );
                });
            }
        });
    }

//    public function scopeUnstable($query)
//    {
//        return $query->where('unstable_price',1);
//    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id')->withTrashed();
    }
}
