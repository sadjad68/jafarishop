<?php

namespace App\Modules\Product\Http\Requests;

use App\Modules\General\Rules\UrlRule;
use App\Modules\Blog\Entities\Blog;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class VariantRequest extends FormRequest
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


    public function rules()
    {
        return [
            'main_variant_specification_id' => 'required',
            'variants' => 'array',
            'variants.*.specifications' => 'array|required', // اعتبارسنجی آرایه مشخصات
            'variants.*.specifications.*' => 'required', // افزودن این خط برای مجاز بودن ناپیدا بودن مقدارهای مشخصه
            'variants.*.stock' => 'required',
            'variants.*.weight' => 'required',
            'variants.*.price' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'main_variant_specification_id.required'=>'  انتخاب مشخصه اصلی الزامیست.',
            'variants.*.specifications.required' => 'مقدار مشخصه الزامی است.',
            'variants.*.specifications.array' => 'مقدار مشخصه باید یک آرایه باشد.',
            'variants.*.specifications.*.required' => 'مقدار مشخصه الزامی است.',
            'variants.*.stock.required' => 'مقدار موجودی الزامی است.',
            'variants.*.weight.required' => 'مقدار وزن الزامی است.',
            'variants.*.price.required' => 'مقدار قیمت الزامی است.',
        ];
    }
}
