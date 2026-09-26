<?php

namespace App\Modules\Comment\Http\Requests;

use App\Modules\Contact\Rules\MobileRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CommentRequest extends FormRequest
{
    public function authorize()
    {
        return true; // You'll want to secure this
    }

    public function rules()
    {
        // Todo: Captcha
        return [
            'name' => 'required',
            'content' => 'required',
            'mobile' => ['required', 'min:11', 'max:11', new MobileRule()],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'نام اجباری است.',
            'content.required' => 'پیام اجباری است.',
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
