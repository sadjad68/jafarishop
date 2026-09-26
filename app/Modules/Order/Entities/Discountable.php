<?php
namespace App\Modules\Order\Entities;
use Illuminate\Database\Eloquent\Model;

class Discountable extends Model
{
    protected $table = 'discountable';
    protected $fillable = [
        'discount_id',
        'discountable_type',
        'discountable_id',
    ];

    public function discount()
    {
        return $this->belongsTo(Discount::class, 'discount_id');
    }

    public function getDiscountableTypeAttribute($value)
    {
        return \App\Services\CmsCoreNamespaceConverter::normalizeClassName($value);
    }

    public function setDiscountableTypeAttribute($value)
    {
        $this->attributes['discountable_type'] = \App\Services\CmsCoreNamespaceConverter::normalizeClassName($value);
    }

    public function discountable()
    {
        return $this->morphTo();
    }
}
