<?php
namespace App\Modules\General\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Modules\General\Helper\NumberHelper;


class CheckNumberRule implements Rule
{
    protected $value;
    protected $title;

    public function __construct($value , $title)
    {
        $this->value = $value;
        $this->title = $title;
    }

    public function passes($attribute, $value)
    {
        $convertedValue = NumberHelper::persian2LatinDigit($this->value);
        $check = $convertedValue >= 0 && $convertedValue <= 9999999999999;

        if ($check || $convertedValue == null) {
            return true;
        } else {
            return false;
        }
    }

    public function message()
    {
        return $this->title.'  باید به صورت عدد باشد ';
    }
}

