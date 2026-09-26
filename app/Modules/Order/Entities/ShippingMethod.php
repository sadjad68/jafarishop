<?php

namespace App\Modules\Order\Entities;

use App\Modules\Comment\Entities\Comment;
use App\Modules\General\Helper\FileManager;
use App\Modules\General\Helper\NumberHelper;
use App\Modules\General\Traits\GlobalScopesTrait;
use App\Modules\General\Traits\Searchable;
use App\Modules\General\Traits\UrlSetterTrait;
use App\Modules\Location\Entities\City;
use App\Modules\Seo\Traits\Seoable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingMethod extends Model
{
    use SoftDeletes;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'price',
        'status',
        'description',
        'freight_balance',
        'price_ceiling',
        'type','chapar_type','config',
        'sender_name',
        'sender_company',
        'sender_city_id',
        'sender_phone',
        'sender_mobile',
        'sender_address',
        'sender_postal_code',
        'sender_email',
    ];


    public function getDateAttribute()
    {
        return jdate('d F Y', $this->updated_at->timestamp);
    }

    public function cities()
    {
        return $this->belongsToMany(City::class, 'shipping_method_city');
    }
    public function sender_city()
    {
        return $this->hasOne(City::class, 'id', 'sender_city_id');
    }
    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = NumberHelper::persian2LatinDigit($value);
    }
    public function setPriceCeilingAttribute($value)
    {
        $this->attributes['price_ceiling'] = NumberHelper::persian2LatinDigit($value);
    }
    public function getStatusNameAttribute()
    {
        return $this->attributes['status'] == 1 ? ['title' => 'نمایش  ', 'badge' => 'success'] : ['title' => 'عدم نمایش  ', 'badge' => 'danger'];
    }
    public function getFreightBalanceNameAttribute()
    {
        return $this->attributes['freight_balance'] == 1 ? ['title' => 'پس کرایه  ', 'badge' => 'success'] : ['title' => 'پیش کرایه  ', 'badge' => 'info'];
    }
    public function getTypeNameAttribute()
    {
        switch ($this->attributes['type']) {
            case "chapar":
                return ['title' => 'چاپار', 'badge' => 'success'];
            case "with_weight":
                return ['title' => 'بر اساس وزن', 'badge' => 'info'];
            default:
                return ['title' => 'با قیمت ثابت', 'badge' => 'info'];
        }
    }

}
