<?php
namespace App\Modules\Blog\Rules;

use App\Modules\Blog\Entities\BlogCategory;
use Illuminate\Contracts\Validation\Rule;

class BlogCategoryRule implements Rule
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function passes($attribute, $value)
    {
        $urls = \Illuminate\Support\Str::slug($value);

        // بررسی کنید آیا آدرس تکراری است، اما با استثناء آیدی مورد نظر
        $check = BlogCategory::where('url', $urls)->where('id', '<>', $this->id)->first();

        if ($check) {
            return false;
        } else {
            return true;
        }
    }

    public function message()
    {
        return 'آدرس تکراری است.';
    }
}

