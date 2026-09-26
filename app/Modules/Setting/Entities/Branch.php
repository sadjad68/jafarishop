<?php

namespace App\Modules\Setting\Entities;

use App\Modules\General\Traits\GlobalScopesTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Branch extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title', 'address', 'map', 'main',
    ];
     use GlobalScopesTrait;

    public function getDateAttribute()
    {
        return jdate('d F Y', $this->updated_at->timestamp);
    }
     public function getMainAddressAttribute()
    {
        return $this->attributes['main'] == 1 ? ['title'=> 'باشد' , 'badge'=>'success']  : ['title'=> 'نمی باشد' , 'badge'=>'danger'];

    }
    public function scopeMainBranch($query)
    {
        return $query->whereMain('1');


    }
}
