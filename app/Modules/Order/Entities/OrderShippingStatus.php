<?php

namespace App\Modules\Order\Entities;

use App\Modules\Comment\Entities\Comment;
use App\Modules\General\Helper\FileManager;
use App\Modules\General\Traits\Searchable;
use App\Modules\General\Traits\UrlSetterTrait;
use App\Modules\Seo\Traits\Seoable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class OrderShippingStatus extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    use Searchable;
    use Seoable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title', 'color', 'default','for_send','sending_sms'

    ];

    public function getDateAttribute()
    {
        $date = jdate('d F Y', $this->updated_at->timestamp);
        return $date;
    }
    public function getDefaultNameAttribute()
    {
        return $this->attributes['default'] == 1 ? ['title' => 'پیشفرض', 'badge' => 'success'] : null;
    }
    public function getSendingSmstNameAttribute()
    {
        return $this->attributes['sending_sms'] == 1 ? ['title' => 'به همراه ارسال پیامک', 'badge' => 'info'] : null;
    }

    public function getSendNameAttribute()
    {
        return $this->attributes['for_send'] == 1 ? ['title' => 'آماده ارسال ', 'badge' => 'success'] : ['title' => 'عادی ', 'badge' => 'warning'];
    }

}
