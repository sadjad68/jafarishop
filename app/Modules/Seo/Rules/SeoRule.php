<?php
namespace App\Modules\Seo\Rules;

use App\Modules\Seo\Entities\SeoMeta;
use Illuminate\Contracts\Validation\Rule;
use App\Modules\Service\Entities\Service;
use Illuminate\Support\Facades\File;

class SeoRule implements Rule
{
    protected $seoable_id;
    protected $seoable_type;
    protected $persian;
    public function __construct($seoable_id,$seoable_type)
    {
        $this->seoable_id = $seoable_id;
        $this->seoable_type = $seoable_type;

    }

    public function passes($attribute, $value)
    {
        $condition = $attribute == "seoTitle" ? 'title_seo': 'description_seo';
        $this->persian = $this->getAttributePersian($condition);
        // بررسی کنید آیا آدرس تکراری است، اما با استثناء آیدی مورد نظر
        $check = SeoMeta::where('seoable_id', '<>', $this->seoable_id)->where('seoable_type', $this->seoable_type)->where($condition,$value)->first();

        if ($check) {
            return false;
        } else {
            return true;
        }
    }
    protected function getAttributePersian($condition)
    {
        return $condition == 'title_seo' ? ' عنوان سئو' : 'توضیحات سئو';
    }
    public function message()
    {
        return $this->persian.' تکراری است. ';
    }
}
