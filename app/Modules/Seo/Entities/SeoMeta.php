<?php

namespace App\Modules\Seo\Entities;

use App\Modules\Seo\Traits\Seoable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class SeoMeta extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'seoable_type', 'seoable_id', 'title_seo', 'description_seo','noindex' ,'h1'
    ];
    use Seoable;
    public function getSeoableTypeAttribute($value)
    {
        return \App\Services\CmsCoreNamespaceConverter::normalizeClassName($value);
    }

    public function setSeoableTypeAttribute($value)
    {
        $this->attributes['seoable_type'] = \App\Services\CmsCoreNamespaceConverter::normalizeClassName($value);
    }

    public function seoable()
    {
        return $this->morphTo();
    }

    public function getDateAttribute()
    {
        $date = jdate('d F Y', $this->updated_at->timestamp);
        return $date;
    }
    public function getnoindexStatusAttribute()
    {
        return $this->attributes['noindex'] == 1 ? ['title'=> 'نوایندکس ' , 'badge'=>'danger']  : ['title'=> 'ایندکس ' , 'badge'=>'success'];

    }
}
