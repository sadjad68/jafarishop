<?php

namespace App\Modules\Product\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Specification extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title', 'type', 'active', 'parent_id', 'is_filter', 'is_color', 'color_code'
    ];

    public function images()
    {
        return $this->hasMany(Image::class, 'specification_id', 'id');
    }

    public function getDateAttribute()
    {
        return jdate('d F Y', $this->updated_at->timestamp);
    }

    public function categories()
    {
        return $this->belongsToMany(ProductCategory::class,
            'category_specification','specification_id','product_category_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_specification');
    }

    public function product_values()
    {
        return $this->hasMany(ProductSpecification::class, 'specification_id');
    }

    public function parent()
    {
        return $this->hasOne(Specification::class, 'id', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Specification::class, 'parent_id')->with('children');
    }
//    public function variants()
//    {
//        return $this->hasMany(ProductVariant::class, 'specification_id');
//    }
    public function variants()
    {
        return $this->belongsToMany(ProductVariant::class, 'product_variant_specification', 'product_variant_id',
        'specification_value_id');
    }
    public function productVariants()
    {
        return $this->belongsToMany(
            ProductVariant::class,
            'product_variant_specifications',
            'specification_value_id',
            'product_variant_id'
        );
    }




    public function getActiveNameAttribute()
    {
        return $this->attributes['active'] == 1 ? ['title' => 'نمایش فعال', 'badge' => 'success'] : ['title' => 'نمایش غیرفعال', 'badge' => 'danger'];

    }

    public function getColorNameAttribute()
    {
        return $this->attributes['is_color'] == 1 ? ['title' => 'فیلتر پالت رنگی', 'badge' => 'success'] : ['title' => 'فیلتر متنی', 'badge' => 'danger'];

    }

    public function getFilterNameAttribute()
    {
        return $this->attributes['is_filter'] == 1 ? ['title' => 'نمایش در فیلتر ', 'badge' => 'success'] : ['title' => 'عدم نمایش در فیلتر ', 'badge' => 'danger'];
    }

    public function getTypeNameAttribute()
    {
        return $this->attributes['type'] == "text" ? 'نوشتاری' : 'انتخابی';
    }

}
