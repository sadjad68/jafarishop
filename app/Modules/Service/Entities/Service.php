<?php

namespace App\Modules\Service\Entities;

use App\Modules\Comment\Entities\Comment;
use App\Modules\Faq\Entities\Faq;
use App\Modules\General\Helper\FileManager;
use App\Modules\General\Helper\NumberHelper;
use App\Modules\General\Traits\GlobalScopesTrait;
use App\Modules\General\Traits\Searchable;
use App\Modules\General\Traits\UrlSetterTrait;
use App\Modules\Seo\Traits\Seoable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes;
    use Seoable;
    use UrlSetterTrait;
    use GlobalScopesTrait;
    use Searchable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'url',
        'status',
        'parent_id',
        'image',
        'show_in_first_page',
        'show_in_menu',
        'show_in_footer',
        'header_image',
        'phone_number',
        'short_description',
        'sort',
        'description_position'
    ];

    public function parent()
    {
        return $this->hasOne(Service::class, 'id', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Service::class, 'parent_id')->orderBy('sort','ASC')->with('children');
    }
    public function childrenInMenu()
    {
        return $this->hasMany(Service::class, 'parent_id')->orderBy('sort','ASC')->where('show_in_menu',1)
            ->with([
                'childrenInMenu' => function ($q) {
                    $q->select('id', 'title', 'url', 'parent_id', 'sort')->where('show_in_menu', 1);
                }
            ]);
    }
    public function fees()
    {
        return $this->hasMany(Fee::class, 'service_id')->orderBy('minimum_price','ASC');
    }

    public function samples()
    {
        return $this->belongsToMany('App\Modules\Service\Entities\WorkSample');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->whereNull('reply_id')->whereStatus(1)->orderBy('created_at', 'desc');
    }

    public function getDateAttribute()
    {
        return jdate('d F Y', $this->updated_at->timestamp);
    }

    public function getShowNameAttribute()
    {
        $text = [];
        if($this->attributes['show_in_first_page']){
            $text[] = "نمایش در صفحه اول";
        }
        if($this->attributes['show_in_menu']){
            $text[] = "نمایش در منو  ";
        }
        if($this->attributes['show_in_footer']){
            $text[] = "نمایش در فوتر  ";
        }
        return $text;
    }
    public function packages()
    {
        return $this->belongsToMany(Package::class, 'package_service');
    }
    public function blogs()
    {
        return $this->belongsToMany('App\Modules\Blog\Entities\Blog', 'service_blog');
    }
    public function getImageAttribute()
    {
        return FileManager::serveFile(
            'uploads/service/big/' . $this->attributes['image'],'assets/notfounds/services-img.jpg'
        );
    }
    public function getHeaderImageAttribute()
    {
        return FileManager::serveFile(
            'uploads/service/' . $this->attributes['header_image'],'assets/notfounds/service-header-detail.jpg'
        );
    }
    public function setPhoneNumberAttribute($value)
    {
        $this->attributes['phone_number'] = NumberHelper::persian2LatinDigit($value);
    }
    public function faqs()
    {
        return $this->morphMany(Faq::class, 'faqable');
    }
}
