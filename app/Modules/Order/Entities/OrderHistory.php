<?php

namespace App\Modules\Order\Entities;


use App\Library\SiteHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use App\Modules\Location\Entities\Address;
use App\Modules\Location\Entities\City;
use App\Modules\Location\Entities\State;
use App\Modules\User\Entities\User;


class OrderHistory extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id', 'shipping_status_id', 'order_status',

    ];
    public function scopeAuthUser($query)
    {

            return $query->where('user_id', Auth::id());

    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function location()
    {
        return $this->hasOne(Address::class, 'id', 'address_id');
    }
    public function state()
    {
        return $this->hasOne(State::class, 'id', 'state_id');
    }
    public function city()
    {
        return $this->hasOne(City::class, 'id', 'city_id');
    }
    public function shipping_status()
    {
        return $this->hasOne(OrderShippingStatus::class, 'id', 'shipping_status_id');
    }
    public function basket()
    {
        return $this->hasOne(Basket::class, 'id', 'basket_id');
    }
    public function bank()
    {
        return $this->hasOne(Bank::class, 'id', 'bank_id');
    }
    public function shipping_method()
    {
        return $this->hasOne(ShippingMethod::class, 'id', 'shipping_method_id');
    }
    public function discount()
    {
        return $this->hasOne(Discount::class, 'id', 'discount_id');
    }
    public function items()
    {
        return $this->hasMany(OrderItem::class)->where('quantity','>',0)->with('product');
    }
    public function getDateAttribute()
    {
        return jdate('d F Y', $this->created_at->timestamp);

    }

    public function getStatusNameAttribute()
    {
        switch ($this->order_status) {
            case 'paying':
                return 'در حال پرداخت';
            case 'paid':
                return 'پرداخت شده';
            case 'deposit_paid':
                return 'بیعانه پرداخت شده';
            case 'wait_for_verification':
                return 'در انتظار تایید پرداخت';
            case 'unpaid':
                return 'پرداخت نشده';
            case 'cancelled':
                return 'لغو شده';
            default:
                return '';
        }
    }
}
