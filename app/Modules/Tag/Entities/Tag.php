<?php

namespace App\Modules\Tag\Entities;

use App\Modules\General\Helper\FileManager;
use App\Modules\General\Traits\GlobalScopesTrait;
use App\Modules\General\Traits\UrlSetterTrait;
use App\Modules\Seo\Traits\Seoable;
use App\Modules\Product\Entities\Product;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Tag extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    use Seoable;
    use UrlSetterTrait;
    use GlobalScopesTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title', 'description', 'url', 'active', 'image',
        'first_page_image' ,
'first_page_icon',
'show_in_first_page',
        'sort'
    ];

    public function products()
    {
        return $this->morphedByMany(Product::class, 'taggable');
    }
    public function getItemImageAttribute()
    {
        return FileManager::serveFile(
            'uploads/tag/' . $this->attributes['image']
        );
    }
    public function getItemFirstPageImageAttribute()
    {
        return FileManager::serveFile(
            'uploads/tag/' . $this->attributes['first_page_image'],'assets/notfounds/discounted-back.jpg'
        );
    }
    public function getItemFirstPageIconAttribute()
    {
        return FileManager::serveFile(
            'uploads/tag/' . $this->attributes['first_page_icon'],'assets/notfounds/Off-Banner.png'
        );
    }
    public function getDateAttribute()
    {
        return jdate('d F Y', $this->updated_at->timestamp);
    }
    public function getActiveNameAttribute()
    {
        return $this->attributes['active'] == 1 ? ['title'=> 'نمایش در صفحه ' , 'badge'=>'success']  : ['title'=> 'عدم نمایش در صفحه ' , 'badge'=>'danger'];
    }
    public function getShowFirstPageNameAttribute()
    {
        return $this->attributes['show_in_first_page'] == 1 ? ['title'=> 'نمایش در صفحه اول ' , 'badge'=>'success']  : ['title'=> 'عدم نمایش در صفحه اول' , 'badge'=>'danger'];
    }
}
