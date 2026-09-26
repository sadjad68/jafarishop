<?php

namespace App\Modules\Setting\Http\Requests;

use App\Modules\Gallery\Rules\GalleryRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class SitemapRequest extends FormRequest
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
            'key' => 'required',
            'p_name'=> 'required',
            'change_frequency'=>'required',
            'priority'=>'required',
        ];
    }

    public function messages()
    {
        return [
            'key.required'=>'ادرس اجباری است.',
            'p_name.required'=>'نام فارسی اجباری است.',
            'change_frequency.required'=>'change_freq اجباری است.',
            'priority.required'=>'priority اجباری است.',
        ];
    }
}
