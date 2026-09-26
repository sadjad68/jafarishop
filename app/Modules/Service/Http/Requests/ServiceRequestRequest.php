<?php

namespace App\Modules\Service\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|regex:/^09[0-9]{9}$/',
            'description' => 'required|string',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
            'service_id' => 'nullable|exists:services,id',
        ];
    }
    protected function prepareForValidation(): void
    {
        if ($this->has('phone')) {
            $this->merge([
                'phone' => $this->convertPersianToEnglish($this->phone),
            ]);
        }
    }
    private function convertPersianToEnglish($string): array|string
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];

        $num = range(0, 9);
        $converted = str_replace($persian, $num, $string);
        return str_replace($arabic, $num, $converted);
    }

    public function messages(): array
    {
        return [
            'full_name.required' => 'نام و نام خانوادگی الزامی است.',
            'full_name.max' => 'نام و نام خانوادگی نباید بیشتر از ۲۵۵ کاراکتر باشد.',
            'phone.required' => 'شماره همراه الزامی است.',
            'phone.regex' => 'فرمت شماره همراه معتبر نیست (مثال: ۰۹۱۲۳۴۵۶۷۸۹).',
            'images.*.image' => 'فایل انتخابی حتماً باید از نوع تصویر باشد.',
            'images.*.mimes' => 'تصویر باید با فرمت‌های jpeg, png, jpg یا webp باشد.',
            'images.*.max' => 'حجم هر تصویر نباید بیشتر از ۵ مگابایت باشد.',
            'images.max' => 'شما نمی‌توانید بیشتر از ۱۰ تصویر آپلود کنید.',
            'service_id.exists' => 'سرویس مورد نظر وجود ندارد !',
            'description.required' => 'توضیحات الزامی است .'
        ];
    }

    public function attributes(): array
    {
        return [
            'full_name' => 'نام و نام خانوادگی',
            'phone' => 'شماره همراه',
            'images.*' => 'تصویر',
        ];
    }
}
