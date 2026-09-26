<?php

namespace App\Modules\Contact\Http\Requests;

use App\Modules\Contact\Rules\MobileRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ContactRequest extends FormRequest
{
    public function authorize()
    {
        return true; // You'll want to secure this
    }

    public function rules()
    {
        // Todo: Captcha
        return [
            'title' => 'required',
            'name' => 'required',
            'message' => 'required',
            'mobile' => ['required', 'min:11', 'max:11', new MobileRule()],
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'عنوان اجباری است.',
            'name.required' => 'نام اجباری است.',
            'message.required' => 'پیام اجباری است.',
            'mobile.required' => 'شماره همراه اجباری است.',
            'mobile.min' => 'شماره همراه باید 11 رقم باشد.',
            'mobile.max' => 'شماره همراه باید 11 رقم باشد.',
        ];
    }

    public function failedValidation($validator)
    {
        $errors = $validator->errors();

        // Return a JSON response with errors
        $response = [
            'success' => false,
            'errors' => $errors,
        ];

        throw new HttpResponseException(
            response()->json($response, 422)
        );
    }
}
