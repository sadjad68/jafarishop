<?php

namespace App\Modules\Service\Entities;

use App\Modules\General\Helper\FileManager;
use App\Modules\General\Helper\NumberHelper;
use App\Modules\General\Traits\GlobalScopesTrait;
use App\Modules\General\Traits\UrlSetterTrait;
use App\Modules\Seo\Traits\Seoable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Package extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    use Seoable;
    use GlobalScopesTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title', 'description', 'url', 'title_seo', 'description_seo', 'status', 'image', 'items', 'show_in_first_page',
        'price', 'discounted_price'
    ];


    public function getImage($size = "big")
    {
        return FileManager::serveFile(
            'uploads/package/' . $size . '/' . $this->attributes['image'],'assets/notfounds/package-img.jpg'
        );
    }

    public function services()
    {
        return $this->belongsToMany('App\Modules\Service\Entities\Service', 'package_service');
    }

    public function assignService($service)
    {

        return $this->services()->sync($service);
    }

    public function getFirstPageNameAttribute()
    {
        return $this->attributes['show_in_first_page'] == 1 ? ['title' => 'نمایش در صفحه اول', 'badge' => 'success'] : ['title' => 'عدم نمایش در صفحه اول', 'badge' => 'danger'];

    }

//    Review : change the name to "data"
    public function getDateAttribute()
    {
        return jdate('d F Y', $this->updated_at->timestamp);
    }

    public function setDiscountedPriceAttribute($value)
    {
        if ($value !== null) {
            $this->attributes['discounted_price'] = intval(NumberHelper::persian2LatinDigit($value));

        }
    }

    public function setPriceAttribute($value)
    {
        if ($value !== null) {
            $this->attributes['price'] = intval(NumberHelper::persian2LatinDigit($value));

        }
    }

    public function getPriceFormatAttribute()
    {
        return $this->attributes['price'];

    }

    public function getDiscountedPriceFormatAttribute()
    {
        return $this->attributes['discounted_price'];
    }

    public function servicesCollection()
    {
        return $this->services()->select(['title']);
    }
}
