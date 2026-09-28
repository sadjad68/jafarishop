<?php

namespace App\Modules\Product\Entities;

use App\Modules\General\Helper\FileManager;
use App\Modules\General\Traits\GlobalScopesTrait;
use App\Modules\General\Traits\Searchable;
use App\Modules\General\Traits\UrlSetterTrait;
use App\Modules\Order\Entities\Discount;
use App\Modules\Seo\Traits\Seoable;
use App\Modules\Product\DTO\ProductCategoryDTO;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class ProductCategory extends Authenticatable
{
    use Notifiable;
    use GlobalScopesTrait;
    use Searchable;
    use Seoable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title', 'description', 'url', 'old_id', 'active', 'show_in_site', 'parent_id', 'image', 'show_in_first_page', 'sort', 'have_price_range', 'min_price', 'max_price',
    ];

    public function specifications()
    {
        return $this->belongsToMany(Specification::class, 'category_specification')->whereNull('category_specification.deleted_at');
    }

    public function parent()
    {
        return $this->hasOne(ProductCategory::class, 'id', 'parent_id');
    }

    public function specificationConditions()
    {
        return $this->belongsToMany(
            Specification::class,
            'category_specification_conditions',
            'category_id',
            'specification_value_id'
        );
    }

    public function children()
    {
        return $this->hasMany(ProductCategory::class, 'parent_id')->orderBy('sort', 'ASC')->where('show_in_site', 1)
            ->with([
                'children' => function ($q) {
                    $q->select('id', 'title', 'url', 'parent_id', 'sort', 'image')->where('show_in_site', 1);
                }
            ]);
    }

    public function childrenInMenu()
    {
        return $this->hasMany(ProductCategory::class, 'parent_id')
            ->orderBy('sort', 'ASC')->whereActive(1)
            ->with([
                'childrenInMenu' => function ($q) {
                    $q->select('id', 'title', 'url', 'parent_id', 'sort', 'image','show_in_site')->where('show_in_site', 1);
                }
            ]);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_category_product', 'product_category_id');
    }

    public function getActiveNameAttribute()
    {
        return $this->attributes['active'] == 1 ? ['title' => 'نمایش در منو', 'badge' => 'success'] : ['title' => 'عدم نمایش در منو', 'badge' => 'danger'];

    }

    public function getShowSiteNameAttribute(): array
    {
        return $this->attributes['show_in_site'] == 1 ? ['title' => 'نمایش در سایت', 'badge' => 'success'] : ['title' => 'عدم نمایش در سایت', 'badge' => 'danger'];

    }

    public function getImage($size = "medium")
    {
        if ($this->attributes['image'] && explode('.', $this->attributes['image'])[1] == "gif") {
            return FileManager::serveFile(
                'uploads/product-category/' . $this->attributes['image'], 'assets/notfounds/category-img.jpg'
            );
        } else {
            return FileManager::serveFile(
                'uploads/product-category/' . $size . '/' . $this->attributes['image'], 'assets/notfounds/category-img.jpg'
            );
        }

    }

    public function getDateAttribute()
    {
        $sourceDate = $this->updated_at ?? $this->created_at;
        return $sourceDate ? jdate('d F Y', $sourceDate) : '';
    }

    public function getFirstPageNameAttribute()
    {
        return $this->attributes['show_in_first_page'] == 1 ? ['title' => 'نمایش در صفحه اول', 'badge' => 'success'] : ['title' => 'عدم نمایش در صفحه اول', 'badge' => 'danger'];
    }

    public function getAllParents($parents = [])
    {
        if ($this->parent) {
            array_unshift($parents, $this->parent); // پدر فعلی را به ابتدای آرایه اضافه کن
            return $this->parent->getAllParents($parents); // ادامه بازگشت
        }
        return $parents; // وقتی دیگر پدر وجود ندارد، آرایه پدرها را برگردان
    }

    public function discounts()
    {
        return $this->belongsToMany(Discount::class, 'discount_product', 'product_category_id', 'discount_id');
    }

}
