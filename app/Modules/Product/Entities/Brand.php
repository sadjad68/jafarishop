<?php

namespace App\Modules\Product\Entities;

use App\Modules\Comment\Entities\Comment;
use App\Modules\General\Helper\FileManager;
use App\Modules\General\Traits\GlobalScopesTrait;
use App\Modules\General\Traits\Searchable;
use App\Modules\General\Traits\UrlSetterTrait;
use App\Modules\Order\Entities\Discount;
use App\Modules\Seo\Traits\Seoable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Brand extends Authenticatable
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
        'title', 'description', 'url', 'active', 'image','show_in_first_page'

    ];
    public function getItemImageAttribute()
    {
        return FileManager::serveFile(
            'uploads/brand/' . $this->attributes['image'],'assets/notfounds/brand.jpg'
        );
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function getDateAttribute()
    {
        $date = jdate('d F Y', $this->updated_at->timestamp);
        return $date;
    }
    public function getActiveNameAttribute()
    {
        return $this->attributes['active'] == 1 ? ['title' => 'نمایش ', 'badge' => 'success'] : ['title' => 'عدم نمایش ', 'badge' => 'danger'];
    }

    public function discounts()
    {
        return $this->belongsToMany(Discount::class, 'discount_product', 'product_category_id', 'discount_id');
    }

}
