<?php

namespace App\Modules\Service\Http\Requests;

use App\Modules\General\Rules\UrlRule;
use App\Modules\Service\Entities\WorkSample;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class WorkSampleRequest extends FormRequest
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

        $rules = [
            'title' => 'required',
            'services' => 'array',
        ];
        // اضافه کردن قانون url به قوانین فقط اگر شرط مورد نظر برقرار باشد
        if ($request->has_page == 1) {
            $rules['url'] = ['required', new UrlRule($id, WorkSample::class)];
        }

        return $rules;
    }
    public function messages()
    {
        return [
            'title.required'=>'  عنوان اجباری است.',
            'url.required' => '  آدرس اجباری است',
            'services.array' => 'خدمات باید آرایه باشد.',

        ];
    }

}
