<?php

namespace App\Modules\Setting\Entities;

use App\Modules\General\Helper\FileManager;
use App\Modules\General\Helper\NumberHelper;
use App\Modules\General\Traits\GlobalScopesTrait;
use App\Modules\General\Traits\UrlSetterTrait;
use App\Modules\Seo\Traits\Seoable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Theme extends Model
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
        'key', 'value', 'p_name', 'type',
    ];


}
