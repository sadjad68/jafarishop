<?php

namespace App\Modules\Gallery\Http\Requests;

use App\Modules\General\Rules\UrlRule;
use App\Modules\Gallery\Entities\GalleryCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class GalleryCategoryRequest extends FormRequest
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
        $id = $request->route('id'); // دریافت آیدی از رویت
        return [
            'title' => 'required',
            'url' => ['required', new UrlRule($id,GalleryCategory::class)],
        ];
    }
    public function messages()
    {
        return [
            'title.required'=>'  عنوان اجباری است.',
            'url.required' => '  آدرس اجباری است',
        ];
    }
}
