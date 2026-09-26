<?php

namespace App\Modules\Service\Entities;

use App\Modules\General\Helper\FileManager;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class WorkSampleImage extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'thumbnail', 'work_sample_id', 'image', 'double_image', 'before_image', 'sort'
    ];

    public function getDateAttribute()
    {
        $date = jdate('d F Y', $this->updated_at->timestamp);
        return $date;
    }

    public function getImage($size = "medium")
    {
        return FileManager::serveFile(
            'uploads/work-sample/' . $size . '/' . $this->attributes['image'],'assets/notfounds/samples-img.jpg'
        );
    }


    public function getBeforeImage($size = "medium")
    {
        return
            FileManager::serveFile(
                'uploads/work-sample/' . $size . '/' . $this->attributes['before_image'],'assets/notfounds/samples-img.jpg');

    }

    public function sample()
    {
        return $this->belongsTo('App\Modules\Service\Entities\WorkSample', 'work_sample_id');
    }

    public function scopeShow($query)
    {
        $records = $query->whereThumbnail('1');
        return $records;
    }

    public function getDoubleAttribute()
    {
        $double = $this->attributes['double_image'] == 1 ? 'نمایش بصورت قبلی-بعدی' : 'نمایش بصورت عادی';
        return $double;
    }
}
