<?php

namespace App\Modules\Service\Entities;

use App\Modules\General\Helper\NumberHelper;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Fee extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'service_fees';
    protected $fillable = [
        'description', 'minimum_price', 'maximum_price', 'service_id',
    ];
    public function service()
    {
        return $this->belongsTo('App\Modules\Service\Entities\Service', 'service_id');
    }
    public function getDateAttribute()
    {
        $date = jdate('d F Y', $this->updated_at->timestamp);
        return $date;
    }
    public function setMinimumPriceAttribute($value)
    {
        if ($value !== null) {
            $this->attributes['minimum_price'] = intval(NumberHelper::persian2LatinDigit($value));

        }
    }
    public function setMaximumPriceAttribute($value)
    {
        if ($value !== null) {
            $this->attributes['maximum_price'] = intval(NumberHelper::persian2LatinDigit($value));

        }
    }
    public function getMinPriceAttribute()
    {
        return number_format($this->attributes['minimum_price']) . ' تومان';

    }
    public function getMaxPriceAttribute()
    {
        return number_format($this->attributes['maximum_price']) . ' تومان';

    }
}
