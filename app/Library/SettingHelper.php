<?php

namespace App\Library;

class SettingHelper
{
    public static  function textToArray($text) {
        $lines = explode(",", @$text);
        return $lines;
    }
}
