<?php

namespace App\Modules\Order\Http\Requests;

use Illuminate\Contracts\Validation\Rule;
use App\Modules\General\Helper\NumberHelper;
use Illuminate\Validation\Rule as ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CountRule implements Rule
{
    protected $count;

    public function __construct($count)
    {
        $this->count = $count;
    }

    public function passes($attribute, $value)
    {
        if (intval(NumberHelper::persian2LatinDigit($this->count)) == 0) {
            return false;
        } else {
            return true;
        }
    }

    public function message()
    {
        return '  تعداد کد مورد نیاز اجباری است.';
    }
}

class DiscountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


    public function rules(Request $request)
    {
        $id = $request->route('id'); // دریافت آیدی از رویت
        return [
            'title' => 'required',
            'amount' => 'required',
            'count' => ['required', new CountRule($request->input('count'))],
            'pay_type' => 'required',
            'product_categories' => ValidationRule::requiredIf($request->pay_type === 'category'),
            'brands' => ValidationRule::requiredIf($request->pay_type === 'brand'),
        ];
    }

    public function messages()
    {
        return [
            'title.required' => '  عنوان اجباری است.',
            'amount.required' => '  مقدار اجباری است.',
            'count.required' => '  تعداد کد مورد نیاز اجباری است',
            'pay_type.required' => 'انتخاب نوع اعمال تخفیف اجباری است.',
            'product_categories.required' => 'انتخاب حداقل یک دسته‌بندی الزامی است.',
            'brands.required' => 'انتخاب حداقل یک برند الزامی است.',
        ];
    }
}
