<?php

namespace App\Modules\Setting\Http\Requests;

use App\Modules\Gallery\Rules\GalleryRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class SocialRequest extends FormRequest
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
            'link' => 'required',
            'icon' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'link.required'=>'  آدرس اجباری است.',
            'icon.required'=>'  نوع اجباری است.',
        ];
    }
}
