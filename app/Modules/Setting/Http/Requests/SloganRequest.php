<?php

namespace App\Modules\Setting\Http\Requests;

use App\Modules\Gallery\Rules\GalleryRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class SloganRequest extends FormRequest
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
        switch ($this->segment(3)) {
            case 'create' :
                return [
                    'value' => 'required',
                    'icon' => 'required',
                ];
                break;
            case 'edit' :
                return [
                    'value' => 'required',
                ];
                break;
        }
    }

    public function messages()
    {
        return [
            'value.required'=>'  شعار اجباری است.',
            'icon.required'=>'  آیکون اجباری است.',
        ];
    }
}
