<?php

namespace App\Modules\General\Helper;

class SettingHelper
{
    public static  function textToArray($text) {
        $lines = explode(",", @$text);
        return $lines;
    }
}
