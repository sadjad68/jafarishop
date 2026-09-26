<?php

namespace App\Modules\User\Entities;

use App\Modules\General\Helper\FileManager;
use App\Modules\General\Helper\NumberHelper;
use App\Modules\General\Traits\GlobalScopesTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Modules\Location\Entities\Address;
use App\Modules\Order\Entities\Basket;
use App\Modules\Order\Entities\Order;
use App\Modules\Service\Entities\Service;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    use GlobalScopesTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    use GlobalScopesTrait;
    protected $fillable = [
        'full_name',
        'mobile',
        'email',
        'avatar',
        'password',
        'show_in_first_page',
        'confirm_code',
        'birthday'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];


    //Relations
    public function roles()
    {
        return $this->belongsToMany(Role::class,'role_user','user_id','role_id');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'user_service');
    }
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'id')->where('order_status', 'paid');
    }
    public function basket()
    {
        return $this->hasOne(Basket::class, 'user_id', 'id')->orderBy('id','DESC');
    }
    public function ordersWithDeposit()
    {
        return $this->hasMany(Order::class, 'user_id', 'id')
            ->where(function ($query) {
                $query->whereIn('order_status', ['paid', 'deposit_paid', 'wait_for_verification'])
                    ->orWhere(function ($q) {
                        $q->where('order_status', 'paying')
                            ->whereHas('bank', function ($bankQuery) {
                                $bankQuery->where('bank_type', 'cardtocard');
                            });
                    });
            })
            ->orderBy('id', 'DESC');
    }
    public function ordersWithDiscount($discountId)
    {
        return $this->hasMany(Order::class, 'user_id', 'id')
            ->where('order_status', 'paid')
            ->where('discount_id', $discountId);
    }


    public function userTypes()
    {
        return $this->hasMany(UserType::class);
    }

    public function syncUserTypes($types)
    {
        $this->userTypes()->whereNotIn('type', $types)->delete();
        foreach ($types as $row) {
            if (!$this->userTypes()->where('type', $row)->first()) {
                $this->userTypes()->create([
                    'type' => $row,
                ]);
            }
        }
    }

    public function getAvatar($size = "big")
    {
        return FileManager::serveFile(
            'uploads/user/' . $size . '/' . $this->attributes['avatar'], 'assets/notfounds/team-img.jpg'
        );
    }
    public function getDashboardAvatar($size = "big")
    {
        return FileManager::serveFile(
            'uploads/user/' . $size . '/' . $this->attributes['avatar'], 'assets/site/images/people.png'
        );
    }

    public function hasAdminPermission(string $routeName): bool
    {
        foreach ($this->roles as $role) {
            $permission = unserialize($role->permission);
            if (is_array($permission) && in_array($routeName, $permission, true)) {
                return true;
            }
        }

        return false;
    }

    public function setMobileAttribute($value)
    {
        $this->attributes['mobile'] = NumberHelper::persian2LatinDigit($value);
    }

    public function getFirstPageNameAttribute()
    {
        return $this->attributes['show_in_first_page'] == 1 ? ['title' => 'نمایش در صفحه اول', 'badge' => 'success'] : ['title' => 'عدم نمایش در صفحه اول', 'badge' => 'danger'];

    }

    public function servicesCollection()
    {
        return $this->belongsToMany('App\Modules\Service\Entities\Service', 'user_service')
            ->select(['title']);
    }
    public function getDateAttribute()
    {
        return jdate('d F Y', $this->created_at->timestamp);
    }
    public function addresses(){
        return $this->hasMany(Address::class);
    }
}
