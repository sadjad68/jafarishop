<?php

namespace App\Modules\Seo\Http\Requests;

use App\Modules\Seo\Rules\SeoRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class SeoRequest extends FormRequest
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
        $seoable_id = $request->get('seoable_id');
        $seoable_type = $request->get('seoable_type');
        return [
            'seoTitle' => ['required', new SeoRule($seoable_id,$seoable_type)],
            'seoDescription' => ['required', new SeoRule($seoable_id,$seoable_type)],
        ];
    }
    public function messages()
    {
        return [
            'seoTitle.required'=>'  عنوان سئو اجباری است.',
            'seoDescription.required'=>'توضیحات سئو اجباری است.',
        ];
    }
}
