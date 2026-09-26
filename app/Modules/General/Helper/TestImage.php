<?php

namespace App\Modules\General\Helper;

class TestImage
{
    public static function is_image($file){
        $fileMimeType = $file->getMimeType();

        $allowedTypes = preg_match('/^image\//', $fileMimeType) === 1;
        return $allowedTypes;
    }
}
