<?php

namespace App\Modules\Product\Http\Requests;

use App\Modules\General\Rules\UrlRule;
use App\Modules\Blog\Entities\Blog;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class SpfRequest extends FormRequest
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

        return [
            'properties' => 'array',
            'properties.*.value' => 'max:255',
            'tags' => 'array',
            'specification_product_values' => 'array',
            'specifications' => 'array',
        ];
    }
    public function messages()
    {
        return [
            'properties.array' => 'ویژگی‌ها باید به صورت آرایه باشند.',
//            'properties.*.value.required' => 'فیلد در هر ویژگی الزامی است.',
//            'properties.*.value.string' => 'فیلد value باید رشته باشد.',
            'properties.*.value.max' => 'ویژگی نباید بیشتر از ۲۵۵ کاراکتر باشد.',

            'tags.array' => 'تگ‌ها باید به صورت آرایه باشند.',
            'specification_product_values.array' => 'مشخصه‌های نوشتاری باید به صورت آرایه باشند.',
            'specifications.array' => 'مشخصه‌های انتخابی باید به صورت آرایه باشند.',
        ];
    }
}
