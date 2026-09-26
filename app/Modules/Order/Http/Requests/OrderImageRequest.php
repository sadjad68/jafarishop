<?php

namespace App\Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderImageRequest extends FormRequest
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
        return self::receiptRules();
    }

    public static function receiptRules(): array
    {
        return [
            'file' => 'required|file|mimes:jpeg,jpg,png,pdf|max:1024',
        ];
    }

    public function messages()
    {
        return self::receiptMessages();
    }

    public static function receiptMessages(): array
    {
        return [
            'file.required' => 'آپلود فیش واریز اجباری است.',
            'file.file' => 'فیش واریز معتبر نیست.',
            'file.mimes' => 'فرمت فیش باید jpg، jpeg، png یا pdf باشد.',
            'file.max' => 'حجم فیش نباید بیشتر از ۱ مگابایت باشد.',
        ];
    }
}
