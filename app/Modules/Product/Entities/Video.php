<?php

namespace App\Modules\Product\Entities;

use App\Modules\Comment\Entities\Comment;
use App\Modules\General\Helper\FileManager;
use App\Modules\General\Traits\UrlSetterTrait;
use App\Modules\Seo\Traits\Seoable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Video extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code', 'active', 'product_id',

    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function getDateAttribute()
    {
        $date = jdate('d F Y', $this->updated_at->timestamp);
        return $date;
    }
    public function getActiveNameAttribute()
    {
        return $this->attributes['active'] == 1 ? ['title' => 'نمایش ', 'badge' => 'success'] : ['title' => 'عدم نمایش ', 'badge' => 'danger'];

    }

}
