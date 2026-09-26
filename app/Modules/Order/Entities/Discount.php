<?php

namespace App\Modules\Order\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Modules\Product\Entities\Brand;
use App\Modules\Product\Entities\ProductCategory;
use App\Modules\User\Entities\User;

class Discount extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title', 'amount', 'type', 'count', 'basket_minimum_price','first_purchase','with_discount','user_id','max_usage_per_user', 'pay_type'

    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function orders()
    {
        return $this->hasMany(Order::class, 'discount_id', 'id');
    }
    public function getDateAttribute()
    {
        $date = jdate('d F Y', $this->updated_at->timestamp);
        return $date;
    }
    public function getFirstPurchaseNameAttribute()
    {
        return $this->attributes['first_purchase'] == 1 ? ['title' => 'خرید اول ', 'badge' => 'success'] :[];
    }
    public function getWithDiscountNameAttribute()
    {
        return $this->attributes['with_discount'] == 1 ? ['title' => 'قابل اعمال روی محصولات تخفیف دار ', 'badge' => 'success'] :[];
    }
    public function getAmountNameAttribute()
    {
        return $this->attributes['type'] == "cash" ? $this->attributes['amount'].' تومان ' : $this->attributes['amount'].' درصد ';
    }

    public function discountable()
    {
        return $this->morphMany(Discountable::class, 'discountable');
    }

    public function productCategories()
    {
        return $this->morphedByMany(ProductCategory::class, 'discountable', 'discountable', 'discount_id','discountable_id'
        );
    }

    public function brands()
    {
        return $this->morphedByMany(Brand::class, 'discountable', 'discountable', 'discount_id','discountable_id');
    }
}
