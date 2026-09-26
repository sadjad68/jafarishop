<?php

namespace App\Modules\Product\Http\Requests;

use App\Modules\General\Rules\UrlRule;
use App\Modules\Blog\Entities\Blog;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ImageRequest extends FormRequest
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
            'image' => 'array',
        ];
    }
    public function messages()
    {
        return [
            'image.array'=>'  تصاویر بصورت آرایه باید باشد.',
        ];
    }
}
