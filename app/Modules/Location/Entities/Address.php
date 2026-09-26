<?php

namespace App\Modules\Location\Entities;

use App\Library\SiteHelper;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use App\Modules\Location\Entities\City;
use App\Modules\Location\Entities\State;
use App\Modules\User\Entities\User;

class Address extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',  'state_id', 'city_id', 'address'
        ,'receiptor_full_name','receiptor_mobile','postal_code'

    ];
    public function scopeAuthUser($query)
    {

            return $query->where('user_id', Auth::id());

    }
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function state()
    {
        return $this->hasOne(State::class, 'id', 'state_id');
    }
    public function city()
    {
        return $this->hasOne(City::class, 'id', 'city_id');
    }

    public function getDateAttribute()
    {
        $date = jdate('d F Y', $this->updated_at->timestamp);
        return $date;
    }

}
