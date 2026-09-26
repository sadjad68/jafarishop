<?php
namespace App\Modules\General\Helper;
use Carbon\Carbon;

class DateHelper
{
    public static function convertDate($date,$hour = 0){
        $date_explode = explode("/", $date);
        $date_jalali = jalali_to_gregorian($date_explode[2], $date_explode[1], $date_explode[0]);
        $date_timestamp = Carbon::create($date_jalali[0], $date_jalali[1], $date_jalali[2], $hour);
        return $date_timestamp;
    }
}
