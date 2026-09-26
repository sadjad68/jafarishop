<?php

namespace App\Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\User\Entities\User;

class ProductNotification extends Model
{
    use SoftDeletes;

    protected $table = 'product_notifications';

    public const TYPE_AVAILABILITY = 'available';
    public const TYPE_DISCOUNT = 'discount';

    public const TYPES = [
        self::TYPE_AVAILABILITY,
        self::TYPE_DISCOUNT,
    ];

    protected $fillable = [
        'user_id',
        'product_id',
        'product_variant_id',
        'type',
        'is_sent',
        'sent_at'
    ];

    protected $casts = [
        'is_sent'=>'boolean',
        'sent_at'=>'datetime'
    ];

    // ---------- relations ----------
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product():BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variant():BelongsTo
    {
        return $this->belongsTo(ProductVariant::class,'product_variant_id')->withTrashed();
    }

    // ---------- scopes ----------
    public function scopeAvailability($q)
    {
        return $q->where('type', self::TYPE_AVAILABILITY);
    }

    public function scopeDiscount($q)
    {
        return $q->where('type', self::TYPE_DISCOUNT);
    }

    // ---------- helpers ----------
    public function isAvailability():bool
    {
        return $this->type === self::TYPE_AVAILABILITY;
    }

    public function isDiscount():bool
    {
        return $this->type === self::TYPE_DISCOUNT;
    }

    public function getJalaliCreateAtWithHour($format = 'd F Y - H:i'): array|string|null
    {
        if ($this->created_at) {
            return jdate($format, $this->created_at);
        }
        return null;
    }
}
