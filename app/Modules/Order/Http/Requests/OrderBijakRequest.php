<?php

namespace App\Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderBijakRequest extends FormRequest
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
            'bijak_image' => 'required|file|image|mimes:jpeg,jpg,png,webp|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'bijak_image.required' => 'انتخاب تصویر بیجک اجباری است.',
            'bijak_image.file' => 'فایل بیجک معتبر نیست.',
            'bijak_image.image' => 'فایل بیجک باید تصویر باشد.',
            'bijak_image.mimes' => 'فرمت تصویر بیجک باید jpg، jpeg، png یا webp باشد.',
            'bijak_image.max' => 'حجم تصویر بیجک نباید بیشتر از ۲ مگابایت باشد.',
        ];
    }
}
