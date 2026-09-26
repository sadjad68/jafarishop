<?php

namespace App\Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
                    'full_name' => 'required',
                    'email' => 'required|email|unique:users,email,NULL,id,deleted_at,NULL',
                    'password' => 'required',
                    'mobile' => 'required|min:11|unique:users,mobile,NULL,id,deleted_at,NULL',
                ];
                break;
            case 'edit' :
                return [
                    'full_name' => 'required',
                    'email' => 'required|email',
                    'mobile' => 'required|min:11',
                ];
                break;
        }
    }

    public function messages()
    {
        return [
            'full_name.required' => 'نام اجباری است.',
            'family.required' => 'نام خانوادگی اجباری است.',
            'email.required' => 'ایمیل اجباری است',
            'password.required' => 'رمز عبور اجباری است',
            'mobile.required' => 'شماره تماس اجباری است',
            'mobile.unique' => 'شماره تماس تکراری است',
            'mobile.min' => 'شماره تماس با باید ۱۱ رقم باشد',
            'email.unique' => 'آدرس ایمیل تکراریست',
        ];
    }
}
