<?php

namespace App\Modules\Blog\Entities;

use App\Modules\General\Helper\FileManager;
use App\Modules\Seo\Traits\Seoable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class BlogCategory extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public const TYPE_TEXT = 'text';
    public const TYPE_VIDEO = 'video';

    protected $fillable = [
        'title', 'description', 'url', 'type', 'title_seo', 'description_seo', 'status', 'parent_id', 'image',
    ];
    use Seoable;

    public function isVideo(): bool
    {
        return ($this->attributes['type'] ?? self::TYPE_TEXT) === self::TYPE_VIDEO;
    }

    public function parent()
    {
        return $this->hasOne(BlogCategory::class, 'id', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(BlogCategory::class, 'parent_id');
    }

    public function blogs()
    {
        return $this->hasMany(Blog::class, 'parent_id');
    }
    public function getItemImageAttribute()
    {
        return FileManager::serveFile(
            'uploads/blog-category/' . $this->attributes['image'],'assets/notfounds/blogs.jpg'
        );
    }
    public function getDateAttribute()
    {
        $date = jdate('d F Y', $this->updated_at->timestamp);
        return $date;
    }
    public function getStatusNameAttribute()
    {
        return $this->attributes['status'] == 1 ? ['title' => 'نمایش در منو ', 'badge' => 'success'] : ['title' => 'عدم نمایش در منو ', 'badge' => 'danger'];
    }
}
