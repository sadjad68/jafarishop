<?php

if (!function_exists('get_ip')) {
    function get_ip()
    {
        if(isset($_SERVER['REMOTE_ADDR'])){
            $ipAddress = $_SERVER['REMOTE_ADDR'];
            if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
                $tmp = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $ipAddress = array_pop($tmp);
            }
            return trim($ipAddress);
        }else{
            return null;
        }
    }
}
