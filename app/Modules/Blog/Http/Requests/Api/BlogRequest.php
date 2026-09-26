<?php

namespace App\Modules\Blog\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Modules\General\Traits\NormalizeSlugTrait;

class BlogRequest extends FormRequest
{
    use NormalizeSlugTrait;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id'); // در صورت آپدیت
        $this->cleanSlug();
        $uniqueUrlRule = Rule::unique('blogs', 'url')->whereNull('deleted_at');
        if ($id) $uniqueUrlRule = $uniqueUrlRule->ignore($id);

        $rules = [
            'title' => 'required|string|max:191',
            'description' => 'nullable|string',
            'url' => ['required', 'string', 'max:191', $uniqueUrlRule,'regex:/^(?!.*[؟؛،٪ـ«»]).[\p{Arabic}a-zA-Z0-9\-\/ ]+$/u'],
            'image' => 'nullable|image|max:2048',
            'status' => 'nullable|boolean',
            'parent_id' => 'nullable|integer|exists:blogs,id',
            'show_in_first_page' => 'nullable|boolean',
            'call_to_action' => 'nullable|boolean',
            'publish_date' => 'nullable|string', // چون در DTO خودت جلالی رو تبدیل می‌کنی
            'author' => 'nullable|string|max:191',
            'services' => 'nullable|array',
            'seo' => ['nullable', 'array'],
            'seo.title_seo' => ['required_with:seo', 'string', 'max:255'],
            'seo.description_seo' => ['required_with:seo', 'string', 'max:255'],
            'seo.h1' => ['nullable', 'string', 'max:255'],
            'seo.noindex' => ['nullable', 'boolean'],

        ];

        // اگر داریم آپدیت می‌کنیم، required → sometimes|required
        if ($id) {
            foreach ($rules as $key => &$rule) {
                if (is_string($rule)) {
                    $rules[$key] = str_replace('required', 'sometimes|required', $rule);
                } elseif (is_array($rule)) {
                    foreach ($rule as $r) {
                        if ($r === 'required') {
                            $rule[] = 'sometimes';
                        }
                    }
                }
            }
            $rules['seo.title_seo'] = ['nullable', 'string', 'max:255'];
            $rules['seo.description_seo'] = ['nullable', 'string', 'max:255'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'title.required' => 'عنوان مقاله الزامی است.',
            'title.string' => 'عنوان باید به صورت متن وارد شود.',
            'title.max' => 'عنوان نمی‌تواند بیشتر از 191 کاراکتر باشد.',

            'description.string' => 'توضیحات باید رشته‌ای از متن باشد.',

            'url.required' => 'آدرس URL مقاله الزامی است.',
            'url.string' => 'آدرس URL باید به صورت رشته‌ای باشد.',
            'url.max' => 'آدرس URL نمی‌تواند بیشتر از 191 کاراکتر باشد.',
            'url.unique' => 'این آدرس قبلاً استفاده شده است.',
            'url.regex' => 'آدرس فقط می‌تواند شامل حروف فارسی، انگلیسی، اعداد، خط تیره و اسلش باشد.',


            'image.image' => 'فایل انتخابی باید تصویر باشد.',
            'image.max' => 'حجم تصویر نباید بیشتر از 2 مگابایت باشد.',

            'status.boolean' => 'وضعیت باید مقدار true یا false داشته باشد.',

            'parent_id.integer' => 'شناسه والد باید عددی باشد.',
            'parent_id.exists' => 'دسته‌ی والد انتخاب‌شده معتبر نیست.',

            'show_in_first_page.boolean' => 'مقدار نمایش در صفحه اصلی باید true یا false باشد.',
            'call_to_action.boolean' => 'مقدار فراخوانی برای اقدام باید true یا false باشد.',

            'publish_date.string' => 'تاریخ انتشار باید به‌صورت رشته ارسال شود.',

            'author.string' => 'نام نویسنده باید رشته‌ای باشد.',
            'author.max' => 'نام نویسنده نمی‌تواند بیشتر از 191 کاراکتر باشد.',

            'services.array' => 'فیلد سرویس‌ها باید آرایه باشد.',

            'seo.array' => 'اطلاعات سئو باید به‌صورت آرایه ارسال شود.',

            'seo.title_seo.required_with' => 'در صورت ارسال اطلاعات سئو، وارد کردن عنوان سئو الزامی است.',
            'seo.title_seo.string' => 'عنوان سئو باید از نوع رشته (متن) باشد.',
            'seo.title_seo.max' => 'عنوان سئو نباید بیشتر از 255 کاراکتر باشد.',

            'seo.h1.string' => 'h1  باید از نوع رشته (متن) باشد.',
            'seo.h1.max' => 'h1  نباید بیشتر از 255 کاراکتر باشد.',

            'seo.description_seo.required_with' => 'در صورت ارسال اطلاعات سئو، وارد کردن توضیح سئو الزامی است.',
            'seo.description_seo.string' => 'توضیحات سئو باید از نوع رشته (متن) باشد.',
            'seo.description_seo.max' => 'توضیحات سئو نباید بیشتر از 255 کاراکتر باشد.',

            'seo.noindex.boolean' => 'مقدار فیلد "نمایش در موتور های جستجو" باید 1 یا 0 باشد.',

        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'خطای اعتبارسنجی در اطلاعات ارسالی.',
            'errors' => $validator->errors(),
        ], 422));
    }
}
