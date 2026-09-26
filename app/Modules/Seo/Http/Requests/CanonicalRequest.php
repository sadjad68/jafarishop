<?php

namespace App\Modules\Seo\Http\Requests;

use App\Modules\Seo\Rules\CanonicalRule;
use App\Modules\Seo\Rules\RedirectRule;
use App\Modules\Seo\Rules\SeoRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CanonicalRequest extends FormRequest
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
        $url = '/'.trim(str_replace(url('/'), "", $request->get('url')),'/');
        return [
            'url' => ['required', new CanonicalRule($url,$id)],
            'canonical' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'url.required'=>'  url اجباری است.',
            'canonical.required'=>'canonical اجباری است.',
        ];
    }
}
