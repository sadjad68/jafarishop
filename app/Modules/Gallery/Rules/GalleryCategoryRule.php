<?php
namespace App\Modules\Gallery\Rules;

use App\Modules\Gallery\Entities\GalleryCategory;
use Illuminate\Contracts\Validation\Rule;

class GalleryCategoryRule implements Rule
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
        $check = GalleryCategory::where('url', $urls)->where('id', '<>', $this->id)->first();

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

