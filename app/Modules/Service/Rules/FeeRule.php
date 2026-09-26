<?php
namespace App\Modules\Service\Rules;

use Illuminate\Contracts\Validation\Rule;

class FeeRule implements Rule
{
    public function passes($attribute, $value)
    {
        // اعتبارسنجی مقدار minimum_price و maximum_price
        $minimumPrice = request()->input('minimum_price');
        $maximumPrice = request()->input('maximum_price');

        // اعتبارسنجی: minimum_price باید کمتر یا مساوی باشد از maximum_price
        return $minimumPrice < $maximumPrice;
    }

    public function message()
    {
        return 'مقدار کمترین باید کمتر از مقدار بیشترین نرخ باشد.';
    }
}


