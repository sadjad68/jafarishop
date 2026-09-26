<?php

namespace App\Modules\Blog\Entities;

use App\Modules\Comment\Entities\Comment;
use App\Modules\General\Helper\FileManager;
use App\Modules\General\Traits\GlobalScopesTrait;
use App\Modules\General\Traits\Searchable;
use App\Modules\Seo\Traits\Seoable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Blog extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    use GlobalScopesTrait;
    use Seoable;
    use Searchable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title', 'description', 'url', 'title_seo', 'description_seo', 'status', 'parent_id', 'image',
        'author','call_to_action','publish_date','view','show_in_first_page'
    ];

    public function getItemImage($size = "big")
    {
        return FileManager::serveFile(
            'uploads/blog/' . $size . '/' . $this->attributes['image'], 'assets/notfounds/blogs.jpg'
        );
    }
    public function category()
    {
        return $this->hasOne(BlogCategory::class, 'id', 'parent_id');
    }
    public function getDateAttribute()
    {
        $date = jdate('d F Y', $this->updated_at->timestamp);
        return $date;
    }
    public function getPublishAttribute()
    {
        return jdate('d F Y', @$this->publish_date);

    }
    public function services()
    {
        return $this->belongsToMany('App\Modules\Service\Entities\Service', 'service_blog');
    }
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->whereNull('reply_id')->whereStatus(1)->orderBy('created_at', 'desc');
    }
    public function assignService($service)
    {

        return $this->services()->sync($service);
    }
}
