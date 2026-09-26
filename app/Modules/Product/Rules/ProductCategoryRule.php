<?php
namespace App\Modules\Product\Rules;

use App\Modules\Blog\Entities\Blog;
use App\Modules\Page\Entities\Page;
use App\Modules\Product\Entities\ProductCategory;
use Illuminate\Contracts\Validation\Rule;

class ProductCategoryRule implements Rule
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    // تابع کمکی برای پیدا کردن سطح دسته
    public function getCategoryLevel($categories, $categoryId)
    {
        $level = 0;
        while ($categoryId) {
            $category = $categories->firstWhere('id', $categoryId);
            if ($category) {
                $level++;
                $categoryId = $category->parent_id;
            } else {
                break;
            }
        }
        return $level;
    }

    public function passes($attribute, $value)
    {
        if ($value) {
            $categories = ProductCategory::all();
            $level = $this->getCategoryLevel($categories, $value);
            return $level < 3;
        }
        return true;
    }

    public function message()
    {
        return 'انتخاب دسته با سطح 3 یا بیشتر مجاز نیست.';
    }
}

