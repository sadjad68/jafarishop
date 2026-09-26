<?php

namespace App\Modules\Order\Http\Requests;

use Illuminate\Validation\Rule;
use App\Modules\General\Rules\UrlRule;
use App\Modules\Product\Entities\Brand;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ShippingMethodRequest extends FormRequest
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
            'sender_name' => 'required',
            'sender_company' => 'required',
            'sender_phone' => 'required',
            'sender_mobile' => 'required',
            'sender_email' => 'required',
            'sender_city_id' => 'required',
            'sender_address' => 'required',
            'sender_postal_code' => 'required',
            'price' => [
                Rule::requiredIf(fn () => request('type') !== 'chapar'),
            ],
            'user_name' => [
                Rule::requiredIf(fn () => request('type') === 'chapar'),
            ],
            'password' => [
                Rule::requiredIf(fn () => request('type') === 'chapar'),
            ],
        ];
    }
    public function messages()
    {
        return [
            'title.required'=>'  عنوان اجباری است.',
            'sender_name.required'=>'  نام فرستنده اجباری است.',
            'sender_company.required'=>'  شرکت فرستند اجباری است.',
            'sender_phone.required'=>'  شماره تلفن فرستنده اجباری است.',
            'sender_mobile.required'=>'  شماره همراه فرستنده اجباری است.',
            'sender_email.required'=>'  ایمیل فرستنده اجباری است.',
            'sender_city_id.required'=>'  شهر فرستنده اجباری است.',
            'sender_address.required'=>'  آدرس فرستنده اجباری است.',
            'sender_postal_code.required'=>'  کد پستی فرستنده اجباری است.',
            'price.required' => '  قیمت اجباری است',
            'user_name.required' => '  نام کاربری اجباری است',
            'password.required' => '  رمز عبور اجباری است',
        ];
    }
}
