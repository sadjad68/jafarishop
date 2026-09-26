<?php

namespace App\Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Comment\Entities\Comment;
use App\Modules\General\Helper\FileManager;
use App\Modules\General\Helper\NumberHelper;
use App\Modules\General\Traits\Searchable;
use App\Modules\General\Traits\UrlSetterTrait;
use App\Modules\Seo\Traits\Seoable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Bank extends Model
{
    use Notifiable;
    use SoftDeletes;
    use Searchable;
    use Seoable;
    protected $appends = ['item_image', 'reservation_expire_minutes'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title', 'icon', 'status', 'admin_test', 'gateway_tariff', 'sort', 'bank_type', 'config',
    ];

    protected $attributes = [
        'gateway_tariff' => 0,
    ];

    public function getItemImageAttribute()
    {
        return (asset('assets/site/bank/' . $this->attributes['icon']));
    }

    public function getDateAttribute()
    {
        $date = jdate('d F Y', $this->updated_at->timestamp);
        return $date;
    }

    public function getStatusNameAttribute()
    {
        return $this->attributes['status'] == 1 ? ['title' => 'نمایش ', 'badge' => 'success'] : ['title' => 'عدم نمایش ', 'badge' => 'danger'];
    }

    public function scopeActive($query)
    {
        return $query->whereStatus('1');


    }

    public function getReservationExpireMinutesAttribute(): ?int
    {
        $config = json_decode($this->attributes['config'] ?? '{}', true) ?: [];
        $raw = $config['reservation_expire_minutes'] ?? ($this->attributes['reservation_expire_minutes'] ?? null);
        if ($raw === null || $raw === '') {
            return null;
        }
        $minutes = intval(NumberHelper::persian2LatinDigit((string) $raw));

        return $minutes > 0 ? $minutes : null;
    }

}
