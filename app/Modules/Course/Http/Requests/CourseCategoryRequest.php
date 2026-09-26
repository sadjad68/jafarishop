<?php

namespace App\Modules\Course\Http\Requests;

use App\Modules\Course\Rules\CourseCategoryRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CourseCategoryRequest extends FormRequest
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
            'active' => 'required',

            'url' => ['required', new CourseCategoryRule($id)],
        ];
    }
    public function messages()
    {
        return [
            'title.required'=>'  عنوان اجباری است.',
            'url.required' => '  آدرس اجباری است',
            'active.required'   => '  وضعیت اجباری است',

        ];
    }
}
