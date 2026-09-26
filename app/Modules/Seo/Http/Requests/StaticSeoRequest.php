<?php

namespace App\Modules\Seo\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class StaticSeoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'title_seo' => 'required',
            'description_seo' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'title_seo.required'=>'  عنوان سئو اجباری است.',
            'description_seo.required'=>'توضیحات سئو اجباری است.',
        ];
    }
}
