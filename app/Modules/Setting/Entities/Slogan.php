<?php

namespace App\Modules\Setting\Entities;

use App\Modules\General\Helper\FileManager;
use App\Modules\General\Helper\NumberHelper;
use App\Modules\General\Traits\GlobalScopesTrait;
use App\Modules\General\Traits\UrlSetterTrait;
use App\Modules\Seo\Traits\Seoable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Slogan extends Model
{
    use Notifiable;
    use UrlSetterTrait;
    use GlobalScopesTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'value', 'icon', 'active'
    ];

    public function getImageAttribute()
    {
        return FileManager::serveFile(
            'uploads/setting/' . $this->attributes['icon'],'assets/notfounds/slogan.jpg'
        );
    }
    public function getDateAttribute()
    {
        $date = jdate('d F Y', $this->updated_at->timestamp);
        return $date;
    }
    public function getActiveNameAttribute()
    {
        return $this->attributes['active'] == 1 ? ['title'=> 'نمایش در صفحه ' , 'badge'=>'success']  : ['title'=> 'عدم نمایش در صفحه ' , 'badge'=>'danger'];

    }
}
