<?php

namespace App\Modules\Gallery\Entities;

use App\Modules\General\Helper\FileManager;
use App\Modules\General\Traits\GlobalScopesTrait;
use App\Modules\Seo\Traits\Seoable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class GalleryCategory extends Model
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
        'title', 'url', 'image','show_in_first_page'
    ];
    public function galleries()
    {
        return $this->hasMany(Gallery::class, 'parent_id', 'id');
    }
    public function getItemImageAttribute()
    {
        return FileManager::serveFile(
            'uploads/gallery-category/' . $this->attributes['image'],'assets/notfounds/gallery-img.jpg'
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
