<?php

namespace App\Modules\Product\Http\Requests;

use App\Modules\General\Helper\NumberHelper;
use App\Modules\General\Rules\UrlRule;
use App\Modules\Blog\Entities\Blog;
use App\Modules\Product\Entities\Product;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class PriceRule implements Rule
{
    protected $price;
    protected $discountedPrice;

    public function __construct($price, $discountedPrice)
    {
        $this->price = NumberHelper::persian2LatinDigit($price);
        $this->discountedPrice = NumberHelper::persian2LatinDigit($discountedPrice);

    }

    public function passes($attribute, $value)
    {
        if ($this->discountedPrice > $this->price) {
            return false;
        } else {
            return true;
        }
    }

    public function message()
    {
        return 'قیمت بعد از تخفیف نمیتواند بیشتر از قیمت اصلی باشد';
    }
}

class ProductRequest extends FormRequest
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
        $id = $request->route('id'); // دریافت آیدی از رویت
        $hasVariants = Product::where('id', $id)->whereHas('variants')->exists();

        return [
            'title' => 'required',
//            'weight' => $hasVariants ? 'nullable' : 'required',
            'weight' => 'nullable',
            'url' => ['required', new UrlRule($id, Product::class)],
            'discounted_price' => [new PriceRule($request->input('price'), $request->input('discounted_price'))],
        ];
    }

    public function messages()
    {
        return [
            'title.required' => '  عنوان اجباری است.',
//            'weight.required' => '  وزن اجباری است.',
            'url.required' => '  آدرس اجباری است',
        ];
    }
}
