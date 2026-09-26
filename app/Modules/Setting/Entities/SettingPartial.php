<?php

namespace App\Modules\Setting\Entities;

use App\Modules\General\Traits\GlobalScopesTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class SettingPartial extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title', 'parent_id','show','sort'
    ];
     use GlobalScopesTrait;


    public function settings()
    {
        return $this->hasMany(Setting::class, 'partial_id', 'id');
    }
    public function parent()
    {
        return $this->hasOne(SettingPartial::class, 'id', 'parent_id')->orderBy('sort','ASC');
    }

    public function children()
    {
        return $this->hasMany(SettingPartial::class, 'parent_id')->with('children')->orderBy('sort','ASC');
    }
    public function getDateAttribute()
    {
        return jdate('d F Y', $this->updated_at->timestamp);
    }
     public function getFirstPageNameAttribute()
    {
        return $this->attributes['show'] == 1 ? ['title'=> 'نمایش ' , 'badge'=>'success']  : ['title'=> 'عدم نمایش ' , 'badge'=>'danger'];

    }
}
