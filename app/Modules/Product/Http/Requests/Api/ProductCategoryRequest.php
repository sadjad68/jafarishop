<?php

namespace App\Modules\Product\Http\Requests\Api;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\General\Traits\NormalizeSlugTrait;

class ProductCategoryRequest extends FormRequest
{
    use NormalizeSlugTrait;
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        // اگر پارامتر روت اسم متفاوتی داره، اینجا عوضش کن
        $id = $this->route('id'); // یا $this->route('category') اگر مدل بایند شده باشه
        $this->cleanSlug();
        $uniqueUrlRule = Rule::unique('product_categories', 'url')->whereNull('deleted_at');

        if ($id) {
            $uniqueUrlRule = $uniqueUrlRule->ignore($id);
        }

        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'show_in_first_page' => 'nullable|boolean',
            'active' => 'nullable|boolean',
            'parent_id' => 'nullable|integer|exists:product_categories,id',
            'image' => 'nullable|image',
            'url' => ['required','string','max:255', $uniqueUrlRule,'regex:/^(?!.*[؟؛،٪ـ«»]).[\p{Arabic}a-zA-Z0-9\-\/ ]+$/u'],

            'seo' => ['nullable', 'array'],
            'seo.title_seo' => ['required_with:seo', 'string', 'max:255'],
            'seo.description_seo' => ['required_with:seo', 'string', 'max:255'],
            'seo.h1' => ['nullable', 'string', 'max:255'],
            'seo.noindex' => ['nullable', 'boolean'],

        ];

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

    public function messages()
    {
        return [
            'title.required' => 'عنوان دسته‌بندی الزامی است.',
            'title.string' => 'عنوان باید رشته باشد.',
            'title.max' => 'عنوان نمی‌تواند بیشتر از 255 کاراکتر باشد.',

            'description.string' => 'توضیحات باید رشته باشد.',

            'show_in_first_page.boolean' => 'مقدار نمایش در صفحه اول باید true یا false باشد.',

            'active.boolean' => 'مقدار active باید true یا false باشد.',

            'parent_id.integer' => 'شناسه والد باید عدد باشد.',
            'parent_id.exists' => 'دسته‌بندی والد معتبر نیست.',

            'image.image' => 'تصویر باید یک فایل تصویری معتبر باشد.',

            'url.required' => 'آدرس URL دسته‌بندی الزامی است.',
            'url.string' => 'آدرس URL باید رشته باشد.',
            'url.max' => 'آدرس URL نمی‌تواند بیشتر از 255 کاراکتر باشد.',
            'url.unique' => 'این آدرس URL قبلا استفاده شده است.',
            'url.regex' => 'آدرس فقط می‌تواند شامل حروف فارسی، انگلیسی، اعداد، خط تیره و اسلش باشد.',
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
            'message' => 'خطای اعتبارسنجی در اطلاعات.',
            'errors' => $validator->errors()
        ], 422));
    }
}
