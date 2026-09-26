<?php

namespace App\Modules\Certification\Http\Requests;

use App\Modules\Gallery\Rules\GalleryRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CertificateRequest extends FormRequest
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
                    'title' => 'required',
                    'image' => 'required',
                ];
                break;
            case 'edit' :
                return [
                    'title' => 'required',

                ];
                break;
        }
    }

    public function messages()
    {
        return [
            'title.required'=>'  عنوان اجباری است.',
            'image.required'=>'  تصویر اجباری است.',
        ];
    }
}
