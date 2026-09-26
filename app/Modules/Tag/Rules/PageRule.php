<?php
namespace App\Modules\Tag\Rules;

use App\Modules\Blog\Entities\Blog;
use App\Modules\Page\Entities\Page;
use Illuminate\Contracts\Validation\Rule;

class PageRule implements Rule
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
        $check = Page::where('url', $urls)->where('id', '<>', $this->id)->first();

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

