<?php

namespace App\Modules\Certification\Entities;

use App\Modules\General\Helper\FileManager;
use App\Modules\General\Traits\GlobalScopesTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Certificate extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title', 'description', 'url', 'image', 'show_in_first_page',
    ];
    use GlobalScopesTrait;

    public function getImage($size = "big")
    {
        return FileManager::serveFile(
            'uploads/certificate/' . $size . '/' . $this->attributes['image'],'assets/notfounds/certificate.jpg'
        );
    }

    public function getDateAttribute()
    {
        return jdate('d F Y', $this->updated_at->timestamp);
    }

    public function getFirstPageNameAttribute()
    {
        return $this->attributes['show_in_first_page'] == 1 ? ['title' => 'نمایش در صفحه اول', 'badge' => 'success'] : ['title' => 'عدم نمایش در صفحه اول', 'badge' => 'danger'];

    }
}
