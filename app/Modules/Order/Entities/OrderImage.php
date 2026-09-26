<?php

namespace App\Modules\Order\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Modules\General\Helper\FileManager;
use App\Modules\Product\Entities\Product;
use App\Modules\Product\Entities\ProductVariant;

class OrderImage extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id', 'file',

    ];

    public function order()
    {
        return $this->hasOne(Order::class, 'id', 'order_id');
    }


    public function getFileAssetAttribute()
    {
        return FileManager::serveFile(
            'uploads/order/' . $this->attributes['order_id'] . '/' . $this->attributes['file'], 'assets/notfounds/default.jpg'
        );
    }

    public function getDateAttribute()
    {
        $date = jdate('d F Y', $this->updated_at->timestamp);
        return $date;
    }
}
