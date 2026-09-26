<?php

namespace App\Library;

class YearHelper
{

    public static function generateNumbersBetween($start, $end) {
    $result = [];
    for ($i = $start; $i <= $end; $i++) {
        $result[] = $i;
    }
        return array_reverse($result);
    }


}
