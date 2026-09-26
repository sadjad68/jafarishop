<?php
namespace App\Modules\Course\Rules;

use App\Modules\Course\Entities\Course;
use Illuminate\Contracts\Validation\Rule;

class CourseRule implements Rule
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
        $check = Course::where('url', $urls)->where('id', '<>', $this->id)->first();

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

