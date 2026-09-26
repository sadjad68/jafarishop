<?php

namespace App\Modules\Page\Entities;

use App\Modules\General\Helper\FileManager;
use App\Modules\General\Traits\GlobalScopesTrait;
use App\Modules\General\Traits\UrlSetterTrait;
use App\Modules\Seo\Traits\Seoable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Page extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title', 'description', 'url', 'title_seo', 'description_seo', 'status', 'parent_id', 'image',
        'show_in_first_page',
        'show_in_footer',
        'show_in_menu',
    ];
    use Seoable;
    use GlobalScopesTrait;

    public function parent()
    {
        return $this->hasOne(Page::class, 'id', 'parent_id');
    }
    public function getItemImageAttribute()
    {
        return FileManager::serveFile(
            'uploads/page/' . $this->attributes['image'],'assets/notfounds/blogs.jpg'
        );
    }
    public function getDateAttribute()
    {
        return jdate('d F Y', $this->updated_at->timestamp);
    }
    public function getFirstPageNameAttribute()
    {
        return $this->attributes['show_in_first_page'] == 1 ? ['title'=> 'نمایش در صفحه اول' , 'badge'=>'success']  : ['title'=> 'عدم نمایش در صفحه اول' , 'badge'=>'danger'];
    }
}
