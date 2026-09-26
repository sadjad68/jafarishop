<?php

namespace App\Modules\Setting\Entities;

use App\Modules\General\Helper\FileManager;
use App\Modules\General\Helper\NumberHelper;
use App\Modules\General\Traits\GlobalScopesTrait;
use App\Modules\General\Traits\UrlSetterTrait;
use App\Modules\Seo\Traits\Seoable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Setting extends Model
{
    use Notifiable;
    use Seoable;
    use UrlSetterTrait;
    use GlobalScopesTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key', 'value', 'p_name', 'type', 'options'
    ];
    protected $casts = [
        'options' => 'array',
    ];

    public function getImageAttribute()
    {
        $image = "default.jpg";
        if (@json_decode($this->attributes['options'])->image){
            $image = json_decode($this->attributes['options'])->image;
        }
        return FileManager::serveFile(
            'uploads/setting/' . $this->attributes['value'], 'assets/notfounds/'.$image
        );
    }

    public function getImageArrayAttribute()
    {
        $files_format = [];
        foreach (explode(',', $this->attributes['value']) as $row) {
            $files_format[] = FileManager::serveFile(
                'uploads/setting/' . $row, 'assets/notfounds/default.jpg'
            );
        }
        return $files_format;
    }

    public function partial()
    {
        return $this->hasOne(SettingPartial::class, 'id', 'parent_id');
    }

    public function sample()
    {
        return $this->belongsToMany('App\Modules\Service\Entities\WorkSample');
    }

    public function assignSample($sample)
    {
        return $this->sample()->sync($sample);
    }

    public function getDateAttribute()
    {
        $date = jdate('d F Y', $this->updated_at->timestamp);
        return $date;
    }
}
