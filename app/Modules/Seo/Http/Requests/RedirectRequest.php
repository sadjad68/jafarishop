<?php

namespace App\Modules\Seo\Http\Requests;

use App\Modules\Seo\Rules\RedirectRule;
use App\Modules\Seo\Rules\SeoRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class RedirectRequest extends FormRequest
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
        $old_address = '/'.trim(str_replace(url('/'), "", $request->get('old_address')),'/');


        return [
            'old_address' => ['required', new RedirectRule($old_address)],
            'new_address' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'old_address.required'=>'  آدرس قدیم اجباری است.',
            'new_address.required'=>'آدرس جدید اجباری است.',
        ];
    }
}
