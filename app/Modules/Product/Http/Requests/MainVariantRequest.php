<?php

namespace App\Modules\Product\Http\Requests;

use App\Modules\General\Rules\UrlRule;
use App\Modules\Blog\Entities\Blog;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class MainVariantRequest extends FormRequest
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
            'main_variant_specification_id' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'main_variant_specification_id.required'=>'  انتخاب مشخصه اصلی الزامیست.',

        ];
    }
}
