<?php

namespace App\Modules\Banner\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class BannerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }


    public function rules(Request $request): array
    {
        return [
            'title' => 'required',
            'image' => $request->is('*edit*') ? 'max:10001' : 'required|max:10001',
            'image_mobile' => $request->is('*edit*') ? 'max:10001' : 'required|max:10001',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'عنوان اجباری است.',
            'image.required' => 'تصویر اجباری است.',
            'image_mobile.required' => 'تصویر موبایل اجباری است.',
            'image.max' => 'حداکثر حجم تصویر ۲ مگابایت میباشد.',
            'image_mobile.max' => 'حداکثر حجم تصویر موبایل ۲ مگابایت میباشد.',
        ];
    }
}
