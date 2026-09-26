<?php

namespace App\Modules\Product\Http\Requests;

use App\Modules\General\Rules\ImageSizeRule;
use App\Modules\General\Rules\UrlRule;
use App\Modules\Service\Entities\Service;
use App\Modules\Product\Entities\ProductCategory;
use App\Modules\Product\Rules\ProductCategoryRule;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ParentIdRule implements Rule
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function passes($attribute, $value)
    {
        if ($this->id && $value == $this->id) {
            return false;
        } else {
            return true;
        }
    }

    public function message()
    {
        return 'نمیتوان زیر مجموعه خودش قرار بگیرد';
    }
}
class PriceRangeRule implements Rule
{
    protected ?string $min_price;
    protected ?string $max_price;
    public function __construct($min_price, $max_price)
    {
        $this->min_price = $min_price !== null ? (string)$min_price : null;
        $this->max_price   = $max_price !== null ? (string)$max_price : null;
    }

    public function passes($attribute, $value)
    {
        if (blank($this->min_price) || blank($this->max_price)) {
            return true;
        }
        if ($this->min_price >= $this->max_price) {
            return false;
        }
        return true;
    }

    public function message()
    {
        return 'قیمت شروع نمی‌تواند بزرگ‌تر یا مساوی قیمت پایان باشد';
    }
}

class ProductCategoryRequest extends FormRequest
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


    public function rules(Request $request)
    {
        $id = $request->route('id');
        return [
            'title' => 'required',
            'url' => ['required', new UrlRule($id, ProductCategory::class)],
            'parent_id' => [new ParentIdRule($id)],
            'image' => ['nullable', 'image', new ImageSizeRule()],
            'min_price' => ['required_if:have_price_range,1', new PriceRangeRule($request['min_price'], $request['max_price'])],
            'max_price' => 'required_if:have_price_range,1'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'عنوان اجباری است.',
            'url.required' => 'آدرس اجباری است',
            'min_price.required_if' => 'شروع قیمت بازه قیمتی اجباری است.',
            'max_price.required_if' => 'پایان قیمت بازه قیمتی اجباری است.',
        ];
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $id = $this->route('id');
            $parentId = $this->input('parent_id');

            if ($id && $parentId) {
                $childIds = $this->getAllChildrenIds($id);

                if (in_array($parentId, $childIds)) {
                    $validator->errors()->add('parent_id', 'نمی‌توان دسته را زیرمجموعه یکی از فرزندانش قرار داد.');
                }
            }
        });
    }

    protected function getAllChildrenIds($parentId)
    {
        $children = ProductCategory::where('parent_id', $parentId)->pluck('id')->toArray();

        foreach ($children as $childId) {
            $children = array_merge($children, $this->getAllChildrenIds($childId));
        }

        return $children;
    }
}
