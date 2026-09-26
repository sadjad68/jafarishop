<?php

namespace App\Modules\Product\Http\Requests;

use App\Modules\General\Rules\UrlRule;
use App\Modules\Product\Entities\Brand;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ImportRequest extends FormRequest
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
            'excel' => 'required',

        ];
    }
    public function messages()
    {
        return [
            'excel.required'=>'  فایل اکسل اجباری است.',
        ];
    }
}
