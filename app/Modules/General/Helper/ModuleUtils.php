<?php

namespace App\Modules\General\Helper;

class ModuleUtils
{
    public static function app_module_path($path){
        return __DIR__."/../../$path";
    }
}
