<?php

namespace App\Modules\Order\Entities;


use App\Library\SiteHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use App\Modules\General\Helper\FileManager;
use App\Modules\Location\Entities\Address;
use App\Modules\Location\Entities\City;
use App\Modules\Location\Entities\State;
use App\Modules\User\Entities\User;


class Order extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id', 'address_id', 'state_id', 'city_id', 'address','receiptor_full_name','shipping_status_id','order_status','basket_id',
        'bank_id','shipping_method_id','discount_id','shipping_price','total_price','discount_price','payment_price','transaction_info',
        'current_tax','tax_price','gateway_tariff','gateway_tariff_price','created_at','updated_at','total_weight','post_code','bijak_image','freight_balance','user_description',
        'deposit_price','remaining_price', 'torob_clid',

    ];
    public function scopeAuthUser($query)
    {

            return $query->where('user_id', Auth::id());

    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id')->withTrashed();
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
        return $this->hasOne(Discount::class, 'id', 'discount_id')->withTrashed();
    }
    public function items()
    {
        return $this->hasMany(OrderItem::class)->where('quantity','>',0)->with('product');
    }
    public function allItems()
    {
        return $this->hasMany(OrderItem::class)->with('product');
    }
    public function images()
    {
        return $this->hasMany(OrderImage::class,'order_id','id');
    }
    public function stockReservations()
    {
        return $this->hasManyThrough(
            ProductStockReservation::class,
            OrderItem::class,
            'order_id',
            'order_item_id',
            'id',
            'id'
        );
    }
    public function getDateAttribute()
    {
        return jdate('d F Y', $this->created_at->timestamp);
    }
    public function getTimeAttribute()
    {
        return jdate('H:i', $this->created_at->timestamp);

    }

    public function getStatusAttribute()
    {
        switch ($this->order_status) {
            case 'paying':
                return  ['title' => 'در حال پرداخت', 'badge' => 'warning'];
            case 'paid':
                return  ['title' => 'پرداخت شده', 'badge' => 'success'];
                case 'deposit_paid':
                return  ['title' => 'بیعانه پرداخت شده', 'badge' => 'success'];
            case 'wait_for_verification':
                return  ['title' => 'در انتظار تایید پرداخت', 'badge' => 'warning'];
            case 'unpaid':
                return  ['title' => 'پرداخت نشده', 'badge' => 'danger'];
            case 'cancelled':
                return  ['title' => 'لغو شده', 'badge' => 'danger'];
            default:
                return null;
        }
    }
    public function getShippingNameAttribute()
    {
        switch (true) {
            case intval($this->shipping_price) == 0 && $this->freight_balance == 0:
                return 'ارسال رایگان';

            case intval($this->shipping_price) != 0 && $this->freight_balance == 0:
                return number_format($this->shipping_price) . ' تومان';

            default:
                return '';
        }
    }

    public function getFreightBalanceNameAttribute()
    {
        return $this->attributes['freight_balance'] == 1 ? ['title' => 'پس کرایه  ', 'badge' => 'success'] : ['title' => 'پیش کرایه  ', 'badge' => 'info'];
    }
    public function hasReturnItem()
    {
        $hasOld = $this->allItems()->whereNotNull('old_quantity')->exists();

        if ($hasOld) {
            return '<span class="badge bg-label-danger">دارای  مرجوعی</span>';
        }

        return null;
    }

    public function getBankTrackingCodeAttribute()
    {
        $transaction = json_decode($this->transaction_info, true);
        $bank_type = @$this->bank->bank_type;
        switch ($bank_type) {
            case "sep":
                return $transaction['verify']['TraceNo'] ?? '';
            case "zarinPal":
                return $transaction['verify']['RefID'] ?? '';
            case "sadad":
                return @$transaction['verify']['RetrivalRefNo'] ?? '';
            default:
                return '';
        }
    }

    public function getOriginalGoodsPriceAttribute(): int
    {
        return intval(str_replace(',', '', (string) $this->total_price));
    }

    public function getBijakImageAssetAttribute()
    {
        if (empty($this->attributes['bijak_image'])) {
            return null;
        }

        $url = FileManager::serveFileWithOutNotFound(
            'uploads/order/' . $this->id . '/' . $this->attributes['bijak_image']
        );

        return $url !== '' ? $url : null;
    }
}
