<?php

namespace App\Modules\Order\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Modules\Product\Entities\Product;
use App\Modules\Product\Entities\ProductVariant;

class OrderItem extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id', 'product_id', 'product_variant_id', 'quantity', 'price','discounted_price','weight','old_quantity'

    ];

    public function order()
    {
        return $this->hasOne(Order::class, 'id', 'order_id');
    }
    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id')->withTrashed();
    }
    public function product_variant()
    {
        return $this->hasOne(ProductVariant::class, 'id', 'product_variant_id')->withTrashed();
    }


    public function getDateAttribute()
    {
        $date = jdate('d F Y', $this->updated_at->timestamp);
        return $date;
    }
}
