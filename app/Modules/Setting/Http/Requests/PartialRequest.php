<?php

namespace App\Modules\Setting\Http\Requests;

use App\Modules\Gallery\Rules\GalleryRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class PartialRequest extends FormRequest
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
            'title' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'title.required'=>'  عنوان اجباری است.',
        ];
    }
}
