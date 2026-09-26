<?php

namespace App\Modules\Order\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Modules\Product\Entities\Product;
use App\Modules\Product\Entities\ProductVariant;

class InventoryTransaction extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
      'product_id', 'product_variant_id', 'quantity','order_id','type','description'

    ];

    public function order()
    {
        return $this->hasOne(Order::class, 'id', 'order_id');
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
