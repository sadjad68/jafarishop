<?php

namespace App\Modules\Service\Http\Requests;

use App\Modules\General\Rules\UrlRule;
use App\Modules\Blog\Entities\Blog;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class FaqRequest extends FormRequest
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
            'faqs' => 'array',
        ];
    }
    public function messages()
    {
        return [
            'faqs.array'=>'  سوالات متداول بصورت آرایه باید باشد.',
        ];
    }
}
