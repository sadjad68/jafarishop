<?php

namespace App\Modules\Comment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Blog\Entities\Blog;
use App\Modules\Product\Entities\Product;
use App\Modules\Service\Entities\Service;
use App\Modules\Service\Entities\WorkSample;
use App\Modules\User\Entities\User;
use App\Services\CmsCoreNamespaceConverter;

class Comment extends Model
{
    use SoftDeletes;

    protected $table = "comments";
    protected $fillable = [
        'mobile',
        'user_id',
        'name',
        'content',
        'commentable_id',
        'commentable_type',
        'reply_id',
        'status',
        'rate',
    ];

    public function getCommentableTypeAttribute($value)
    {
        return CmsCoreNamespaceConverter::normalizeClassName($value);
    }

    public function setCommentableTypeAttribute($value)
    {
        $this->attributes['commentable_type'] = CmsCoreNamespaceConverter::normalizeClassName($value);
    }

    public function commentable()
    {
        return $this->morphTo()->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function replies()
    {
        return $this->hasMany('App\Modules\Comment\Entities\Comment', 'reply_id')->where('status', 1);
    }

    public function comment()
    {
        return $this->hasOne('App\Modules\Comment\Entities\Comment', 'id', 'reply_id')->withTrashed();
    }

    public function getStatusItemAttribute()
    {
        $status = $this->attributes['status'] ?? null;
        return (int) $status === 1
            ? ['title' => 'منتشر شده', 'badge' => 'success']
            : ['title' => 'عدم انتشار', 'badge' => 'danger'];
    }

    public function getDateAttribute()
    {
        try {
            $date = $this->updated_at ?? $this->created_at;
            if ($date === null) {
                return '—';
            }
            return jdate('d F Y', $date->timestamp);
        } catch (\Throwable $e) {
            return '—';
        }
    }

    public function getModelNameAttribute()
    {
        if ($this->commentable_type === Blog::class) {
            return 'مطلب';
        } elseif ($this->commentable_type === Service::class) {
            return 'خدمات';
        } elseif ($this->commentable_type === WorkSample::class) {
            return 'نمونه کار';
        } elseif ($this->commentable_type === Product::class) {
            return 'محصولات';
        } else {
            return '';
        }
    }
    public function getCommentUrlAttribute()
    {
        $routeNames = [
            Blog::class => 'blog.detail',
            Service::class => 'service.detail',
            WorkSample::class => 'portfolio.detail',
            Product::class => 'product.detail',
        ];

        $routeName = $routeNames[$this->commentable_type] ?? null;
        $commentable = $this->commentable;

        if ($routeName && $commentable && ! empty($commentable->url ?? null)) {
            try {
                if ($commentable instanceof Product) {
                    return \App\Library\SiteUrl::product($commentable);
                }

                return route($routeName, $commentable->url);
            } catch (\Throwable $e) {
                return '';
            }
        }

        return '';
    }

}
