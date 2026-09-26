<?php

namespace App\Modules\Product\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Modules\General\Traits\NormalizeSlugTrait;

class ProductRequest extends FormRequest
{
    use NormalizeSlugTrait;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');

        $this->cleanSlug();
        $uniqueUrl = Rule::unique('products', 'url')->whereNull('deleted_at');

        if ($id) $uniqueUrl->ignore($id);

        $rules = [
            'title' => 'required|string|max:255',
            'url' => ['required','string','max:255', $uniqueUrl,'regex:/^(?!.*[؟؛،٪ـ«»]).[\p{Arabic}a-zA-Z0-9\-\/ ]+$/u'],
            'price' => 'nullable|numeric|min:0',
            'discounted_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'active' => 'nullable|boolean',
            'brand_id' => 'nullable|exists:brands,id',
            'weight' => 'nullable|numeric|min:0',
            'main_variant_specification_id' => 'nullable|integer|exists:specifications,id',
            'stock' => 'nullable|integer|min:0',
            'show_in_first_page' => 'nullable|boolean',
            'start_timer' => 'nullable|date',
            'end_timer' => 'nullable|date|after_or_equal:start_timer',

            'categories' => 'nullable|array',
            'categories.*' => 'integer|exists:product_categories,id',

            'tags' => 'nullable|array',
            'tags.*' => 'integer|exists:tags,id',

            'related' => 'nullable|array',
            'related.*' => 'integer|exists:products,id',

            'complement' => 'nullable|array',
            'complement.*' => 'integer|exists:products,id',

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

    public function messages(): array
    {
        return [
            'title.required' => 'عنوان محصول الزامی است.',
            'title.string' => 'عنوان باید رشته باشد.',
            'title.max' => 'عنوان نمی‌تواند بیش از ۲۵۵ کاراکتر باشد.',

            'url.required' => 'آدرس محصول الزامی است.',
            'url.string' => 'آدرس محصول باید رشته باشد.',
            'url.max' => 'آدرس محصول نمی‌تواند بیش از ۲۵۵ کاراکتر باشد.',
            'url.unique' => 'این آدرس قبلاً برای محصول دیگری ثبت شده است.',
            'url.regex' => 'آدرس فقط می‌تواند شامل حروف فارسی، انگلیسی، اعداد، خط تیره و اسلش باشد.',

            'price.numeric' => 'قیمت باید عددی باشد.',
            'price.min' => 'قیمت نمی‌تواند منفی باشد.',

            'discounted_price.numeric' => 'قیمت با تخفیف باید عددی باشد.',
            'discounted_price.min' => 'قیمت با تخفیف نمی‌تواند منفی باشد.',

            'description.string' => 'توضیحات باید رشته باشد.',

            'active.boolean' => 'وضعیت فعال بودن باید true یا false باشد.',

            'brand_id.exists' => 'برند انتخاب‌شده معتبر نیست.',

            'weight.numeric' => 'وزن باید عددی باشد.',
            'weight.min' => 'وزن نمی‌تواند منفی باشد.',

            'main_variant_specification_id.integer' => 'شناسه ویژگی اصلی باید عددی باشد.',
            'main_variant_specification_id.exists' => 'شناسه ویژگی اصلی معتبر نیست.',

            'stock.integer' => 'موجودی باید عددی باشد.',
            'stock.min' => 'موجودی نمی‌تواند منفی باشد.',

            'show_in_first_page.boolean' => 'مقدار نمایش در صفحه اول باید true یا false باشد.',

            'start_timer.date' => 'زمان شروع باید به‌صورت تاریخ معتبر باشد.',
            'end_timer.date' => 'زمان پایان باید به‌صورت تاریخ معتبر باشد.',
            'end_timer.after_or_equal' => 'زمان پایان نمی‌تواند قبل از زمان شروع باشد.',

            'categories.array' => 'دسته‌بندی‌ها باید آرایه باشند.',
            'categories.*.integer' => 'شناسه هر دسته‌بندی باید عدد باشد.',
            'categories.*.exists' => 'یکی از دسته‌بندی‌های انتخاب‌شده معتبر نیست.',

            'tags.array' => 'تگ‌ها باید آرایه باشند.',
            'tags.*.integer' => 'شناسه هر تگ باید عدد باشد.',
            'tags.*.exists' => 'یکی از تگ‌های انتخاب‌شده معتبر نیست.',

            'related.array' => 'محصولات مرتبط باید آرایه باشند.',
            'related.*.integer' => 'شناسه هر محصول مرتبط باید عدد باشد.',
            'related.*.exists' => 'یکی از محصولات مرتبط معتبر نیست.',

            'complement.array' => 'محصولات مکمل باید آرایه باشند.',
            'complement.*.integer' => 'شناسه هر محصول مکمل باید عدد باشد.',
            'complement.*.exists' => 'یکی از محصولات مکمل معتبر نیست.',

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
            'errors' => $validator->errors(),
        ], 422));
    }
}
