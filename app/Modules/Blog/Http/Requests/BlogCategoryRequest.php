<?php

namespace App\Modules\Blog\Http\Requests;

use App\Modules\General\Rules\UrlRule;
use App\Modules\Course\Rules\CourseCategoryRule;
use App\Modules\Blog\Entities\BlogCategory;
use App\Modules\Blog\Rules\BlogCategoryRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class BlogCategoryRequest extends FormRequest
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
            'url' => ['required', new UrlRule($id,BlogCategory::class)],
            'type' => ['nullable', 'in:text,video'],
        ];
    }
    public function messages()
    {
        return [
            'title.required'=>'  عنوان اجباری است.',
            'url.required' => '  آدرس اجباری است',
            'type.in' => 'نوع دسته‌بندی باید متن یا ویدیو باشد.',
        ];
    }
}
