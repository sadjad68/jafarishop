<?php

namespace App\Modules\Course\Entities;

use App\Modules\Comment\Entities\Comment;
use App\Modules\General\Traits\UrlSetterTrait;
use App\Modules\Seo\Traits\Seoable;
use App\Modules\Course\Entities\Course;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Session extends Model
{
    use Seoable;use UrlSetterTrait;use SoftDeletes;

    protected $fillable = [
        'title',
        'free',
        'description',
        'thumbnail',
        'hours',
        'minutes',
        'active',
        'course_id',
        'sort',
    ];

    public function sessionFiles()
    {
        return $this->hasMany(SessionFile::class);
    }

    public function getItemImageAttribute()
    {
        if ($this->attributes['thumbnail']) {
            return file_exists('assets/uploads/session/' . $this->attributes['thumbnail']) ? asset('assets/uploads/session/' . $this->attributes['thumbnail']) : asset('assets/notfounds/default.jpg');
        } else {
            return asset('assets/notfounds/default.jpg');
        }
    }

    public function getDateAttribute()
    {
        $date = jdate('d F Y', $this->updated_at->timestamp);
        return $date;
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->orderBy('created_at', 'desc');
    }

    public function getModelNameAttribute()
    {
        return 'جلسات';
    }
}
