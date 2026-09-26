<?php

namespace App\Modules\Banner\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class HighlightRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(Request $request): array
    {
        $isEdit = $request->is('*edit*') || $request->routeIs('admin.highlight.edit');

        return [
            'title' => 'required',
            'target' => 'required|in:place,tag',
            'tag_id' => 'required_if:target,tag|nullable|exists:tags,id',
            'place' => 'required_if:target,place|nullable|string',
            'image' => $isEdit ? 'nullable|max:10001' : 'required|max:10001',
            'link' => 'nullable|string|max:2048',
            'width' => 'nullable|numeric',
            'height' => 'nullable|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'عنوان اجباری است.',
            'image.required' => 'تصویر اجباری است.',
            'place.required_if' => 'مکان قرارگیری اجباری است.',
            'tag_id.required_if' => 'انتخاب تگ اجباری است.',
            'target.required' => 'نوع نمایش (جایگاه یا تگ) اجباری است.',
            'image.max' => 'حداکثر حجم تصویر ۲ مگابایت میباشد.',
        ];
    }
}
