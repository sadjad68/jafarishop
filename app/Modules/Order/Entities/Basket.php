<?php

namespace App\Modules\Order\Entities;

use App\Library\SiteHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use App\Modules\Location\Entities\Address;
use App\Modules\User\Entities\User;

class Basket extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id', 'address_id', 'bank_id', 'shipping_method_id', 'discount_id', 'user_cookie', 'torob_clid',

    ];
    public function scopeAuthUser($query)
    {
        $site_name = @SiteHelper::getInformation()['site_name'];
        if (Auth::check()) {
            return $query->where('user_id', Auth::id());
        } else {
            if (session()->get('custom_data') == null) {
                session()->put('custom_data', $site_name . strtotime(Carbon::now()));
                session()->save();
            }
            return $query->whereNotNull('user_cookie')->where('user_cookie', session()->get('custom_data'));
        }
    }
    public function scopeCookieUser($query)
    {
        $site_name = @SiteHelper::getInformation()['site_name'];
        if (session()->get('custom_data') == null) {
            session()->put('custom_data', $site_name . strtotime(Carbon::now()));
            session()->save();

        }
        return $query->whereNotNull('user_cookie')->where('user_cookie', session()->get('custom_data'));
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function address()
    {
        return $this->hasOne(Address::class, 'id', 'address_id');
    }
    public function bank()
    {
        return $this->hasOne(Bank::class, 'id', 'bank_id');
    }
    public function shippingMethod()
    {
        return $this->hasOne(ShippingMethod::class, 'id', 'shipping_method_id');
    }
    public function discount()
    {
        return $this->hasOne(Discount::class, 'id', 'discount_id');
    }
    public function items()
    {
        return $this->hasMany(BasketItem::class)->where('quantity', '>', 0)->orderBy('id','DESC')->with('product');
    }

    public function getFinalPriceAttribute()
    {
        $final_price_sum = 0;

        foreach ($this->items as $item) {

            $variant = $item->productVariant;
            $product = $item->product;

            if ($variant) {
                $final = $variant->final_price ?? 0;
            } elseif ($product) {
                $final = $product->final_price ?? 0;
            } else {
                $final = 0;
            }

            $final_price_sum += $final * ($item->quantity ?? 0);
        }

        return $final_price_sum;
    }


    public function getDateAttribute()
    {
        $date = jdate('d F Y', $this->updated_at->timestamp);
        return $date;
    }
}
