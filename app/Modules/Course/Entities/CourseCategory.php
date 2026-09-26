<?php

namespace App\Modules\Course\Entities;

use App\Modules\General\Traits\UrlSetterTrait;
use App\Modules\Seo\Traits\Seoable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseCategory extends Model
{
    use Seoable;use UrlSetterTrait;use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'url',
        'image',
        'active',
    ];

    public function getItemImageAttribute()
    {
        if ($this->attributes['image']) {
            return file_exists('assets/uploads/course-category/' . $this->attributes['image']) ? asset('assets/uploads/course-category/' . $this->attributes['image']) : asset('assets/notfounds/default.jpg');
        } else {
            return asset('assets/notfounds/default.jpg');
        }
    }
    public function getDateAttribute()
    {
        $date = jdate('d F Y', $this->updated_at->timestamp);
        return $date;
    }
}
