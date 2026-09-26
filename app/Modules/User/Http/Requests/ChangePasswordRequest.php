<?php

namespace App\Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
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
        return [
            'password' => 'min:6',
            're_password' => 'required_with:password|same:password|min:6'
        ];
    }

    public function messages()
    {
        return [
            'password.required' => 'رمز عبور اجباری است.',
            'password.min' => 'حداقل ۶ کاراکتر برای رمز عبور وارد کنید',
            're_password.same' => 'تکرار رمز عبور صحیح نمیباشد',
            're_password.min' => 'حداقل ۶ کاراکتر برای تکرار رمز عبور وارد کنید',
            're_password.required' => 'تکرار رمز عبور اجباری است.',
        ];
    }
}
