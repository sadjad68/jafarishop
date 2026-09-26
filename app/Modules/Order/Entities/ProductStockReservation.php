<?php

namespace App\Modules\Order\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Modules\Product\Entities\Product;
use App\Modules\Product\Entities\ProductVariant;

class ProductStockReservation extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    protected $fillable = [
        'order_item_id',
        'product_id',
        'product_variant_id',
        'quantity',
        'status',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function orderItem()
    {
        return $this->hasOne(OrderItem::class, 'id', 'order_item_id');
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function productVariant()
    {
        return $this->hasOne(ProductVariant::class, 'id', 'product_variant_id');
    }

    public function scopeReserved($query)
    {
        return $query->where('status', 'reserved');
    }

    public function scopeExpired($query)
    {
        return $query->reserved()->whereNotNull('expires_at');
//            ->where('expires_at', '<=', now());
    }
}
