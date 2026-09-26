<?php
namespace App\Modules\General\Rules;

use Illuminate\Contracts\Validation\Rule;

class UrlRule implements Rule
{
    protected $id;
    protected $modelName;

    public function __construct($id, $modelName)
    {
        $this->id = $id;
        $this->modelName = $modelName;
    }
    public function passes($attribute, $value)
    {

        $urlValue = trim(str_replace(' ', '-', $value));

        if (!$this->isUnique($urlValue)) {
            $this->message = 'آدرس تکراری است.';
            return false;
        }

        if (!$this->containsValidChars($urlValue)) {
            $this->message = 'آدرس حاوی کاراکترهای غیرمجاز است.';
            return false;
        }

        return true;
    }
    protected function containsValidChars($urlValue)
    {

        return !preg_match('/[\'^£$%&*()}{@#~?><>,|=:.\/]/', $urlValue);
    }
    protected function isUnique($urlValue)
    {
        $namespacedModel =  $this->modelName;
        // بررسی کنید آیا آدرس تکراری است، اما با استثناء آیدی مورد نظر
        $check = $namespacedModel::orderBy('id', 'DESC')
            ->where('url', $urlValue)
            ->where('id', '<>', $this->id)
            ->first();
        return !$check;
    }

    public function message()
    {
        return $this->message ?? 'خطایی رخ داده است.';
    }
}


