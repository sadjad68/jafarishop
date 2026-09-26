<?php

namespace App\Modules\Order\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Modules\Product\Entities\Product;
use App\Modules\Product\Entities\ProductVariant;

class BasketItem extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'basket_id', 'product_id', 'product_variant_id', 'quantity',

    ];

    public function basket()
    {
        return $this->hasOne(Basket::class, 'id', 'basket_id');
    }
    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
    public function productVariant()
    {
        return $this->hasOne(ProductVariant::class, 'id', 'product_variant_id');
    }


    public function getDateAttribute()
    {
        $date = jdate('d F Y', $this->updated_at->timestamp);
        return $date;
    }
}
