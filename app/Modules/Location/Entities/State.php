<?php

namespace App\Modules\Location\Entities;

use App\Modules\General\Helper\FileManager;
use App\Modules\Seo\Traits\Seoable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class State extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'status','chapar_id'
    ];
    public function getStatusNameAttribute()
    {
        return $this->attributes['status'] == 1 ? ['title' => 'نمایش  ', 'badge' => 'success'] : ['title' => 'عدم نمایش  ', 'badge' => 'danger'];
    }
    public function cities(){
        return $this->hasMany(City::class);
    }
}
