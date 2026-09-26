<?php

namespace App\Modules\Order\Http\Requests;

use App\Modules\General\Rules\UrlRule;
use App\Modules\Product\Entities\Brand;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class OrderShippingStatusRequest extends FormRequest
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

        return [
            'title' => 'required',
            'color' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'title.required'=>'  عنوان اجباری است.',
            'color.required' => '  رنگ اجباری است',
        ];
    }
}
