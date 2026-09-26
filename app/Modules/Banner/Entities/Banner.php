<?php

namespace App\Modules\Banner\Entities;

use App\Modules\General\Helper\FileManager;
use App\Modules\General\Traits\GlobalScopesTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Banner extends Model
{
    use Notifiable;
    use SoftDeletes;
    use GlobalScopesTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'show_in_first_page', 'title', 'image','image_mobile','link', 'sort',
    ];

    public function getImageAttribute()
    {
        $theme_provider = app(\App\Modules\General\Helper\ThemeProvider::class);
            $file = 'assets/notfounds/slider-desktop-'.$theme_provider->getValue().'.jpg';
        return FileManager::serveFile(
            'uploads/banner/big/' . $this->attributes['image'],$file
        );
    }
    public function getImageMobileAttribute()
    {
        $theme_provider = app(\App\Modules\General\Helper\ThemeProvider::class);
        $file = 'assets/notfounds/slider-mobile-'.$theme_provider->getValue().'.jpg';

        return FileManager::serveFile(
            'uploads/banner/big/' . $this->attributes['image_mobile'],$file
        );
    }

    public function getFirstPageNameAttribute()
    {
        return $this->attributes['show_in_first_page'] == 1 ?
            ['title' => 'نمایش در صفحه اول', 'badge' => 'success']
        :
            ['title' => 'عدم نمایش در صفحه اول', 'badge' => 'danger'];
    }
}
