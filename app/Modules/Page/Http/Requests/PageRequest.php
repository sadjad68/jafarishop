<?php

namespace App\Modules\Page\Http\Requests;

use App\Modules\General\Rules\UrlRule;
use App\Modules\Blog\Rules\BlogRule;
use App\Modules\Page\Entities\Page;
use App\Modules\Page\Rules\PageRule;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
class ParentIdRule implements Rule
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function passes($attribute, $value)
    {
        if ($value == $this->id) {
            return false;
        } else {
            return true;
        }
    }

    public function message()
    {
        return 'نمیتوان زیر مجموعه خودش قرار بگیرد';
    }
}
class PageRequest extends FormRequest
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
                    'url' => ['required', new UrlRule($id,Page::class)],

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
