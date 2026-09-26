<?php

namespace App\Modules\Gallery\Entities;

use App\Modules\General\Helper\FileManager;
use App\Modules\General\Traits\GlobalScopesTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Gallery extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title', 'description', 'url', 'title_seo', 'description_seo', 'status', 'parent_id', 'file','show_in_first_page'
    ];
     use GlobalScopesTrait;

    public function getItemImageAttribute()
    {
        return FileManager::serveFile(
            'uploads/gallery/' . $this->attributes['file'],'assets/notfounds/gallery-img.jpg'
        );
    }
    public function category()
    {
        return $this->hasOne(GalleryCategory::class, 'id', 'parent_id');
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
