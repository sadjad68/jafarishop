<?php

namespace App\Modules\Service\Http\Requests;

use App\Modules\General\Rules\UrlRule;
use App\Modules\Service\Entities\Package;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
class PriceRule implements Rule
{
    protected $price;
    protected $discountedPrice;

    public function __construct($price, $discountedPrice)
    {
        $this->price = $price;
        $this->discountedPrice = $discountedPrice;
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

class PackageRequest extends FormRequest
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

        switch ($this->segment(3)) {
            case 'create' :
                return [
                    'title' => 'required',
                    'url' => ['required', new UrlRule($id,Package::class)],
                    'image' => 'required',
                    'services' => 'array',
                    'discounted_price' => [new PriceRule($request->input('price'), $request->input('discounted_price'))],

                ];
                break;
            case 'edit' :
                return [
                    'title' => 'required',
                    'url' => ['required', new UrlRule($id,Package::class)],
                    'services' => 'array',
                    'discounted_price' => [new PriceRule($request->input('price'), $request->input('discounted_price'))],


                ];
                break;
        }
    }
    public function messages()
    {
        return [
            'title.required'=>'  عنوان اجباری است.',
            'url.required' => '  آدرس اجباری است',
            'image.required' => '  تصویر اجباری است',
            'services.array' => 'خدمات باید آرایه باشد.',

        ];
    }
}
