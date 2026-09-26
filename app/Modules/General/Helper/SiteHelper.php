<?php

namespace App\Modules\General\Helper;

use Illuminate\Support\Facades\Storage;

class SiteHelper
{
    public static function getInformation()
    {
        return \App\Library\SiteHelper::getInformation();
    }
}
