<?php
namespace App\Modules\General\Rules;

use Illuminate\Contracts\Validation\Rule;


class ImageSizeRule implements Rule
{
    /**
     * Create a new rule instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // اگر فایل وجود نداشت، نیازی به بررسی نیست
        if (!$value) {
            return true; // فایل اختیاری است
        }

        // چک کردن اگر فایل GIF است
        if ($value->getClientOriginalExtension() === 'gif') {
            // چک کردن حجم فایل (بایت به کیلوبایت تبدیل شود)
            $maxSizeInKB = 300; // حداکثر 300 کیلوبایت
            return $value->getSize() <= ($maxSizeInKB * 1024);
        }

        // برای فایل‌های غیر GIF نیازی به بررسی نیست
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'اگر فایل gif است، حجم آن نباید بیشتر از ۳۰۰ کیلوبایت باشد.';
    }
}


