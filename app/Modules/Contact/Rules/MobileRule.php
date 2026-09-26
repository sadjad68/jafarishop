<?php

namespace App\Modules\Contact\Rules;


use App\Modules\General\Helper\NumberHelper;
use Illuminate\Contracts\Validation\Rule;

class MobileRule implements Rule
{
    public function passes($attribute, $value):bool
    {
        $pattern = '/^(\+98|0|0098)?9\d{9}$/';
        $parsed_mobile = NumberHelper::persian2LatinDigit($value);
        if (preg_match($pattern, $parsed_mobile)) {
            return true;
        } else {
            return false;
        }
    }

    public function message() : string
    {
        return 'شماره موبایل صحیح نمیباشد.';
    }
}
