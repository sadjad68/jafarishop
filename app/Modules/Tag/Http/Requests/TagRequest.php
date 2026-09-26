<?php

namespace App\Modules\Tag\Http\Requests;

use App\Modules\General\Rules\UrlRule;
use App\Modules\Tag\Entities\Tag;
use App\Modules\Blog\Rules\BlogRule;
use App\Modules\Page\Entities\Page;
use App\Modules\Page\Rules\PageRule;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
class TagRequest extends FormRequest
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
            'url' => ['required', new UrlRule($id,Tag::class)],
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
