<?php

namespace App\Modules\Course\Http\Requests;

use Illuminate\Http\Request;
use App\Modules\General\Rules\CheckNumberRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Course\Rules\CourseRule;

class CourseRequest extends FormRequest
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
        $pric = $request->price;
        $discounted_price = $request->discounted_price;
        return [
            'title' => 'required',
            'price' => ['required', new CheckNumberRule($pric, 'قیمت')],
            'hours' => 'required',
            'minutes' => 'required',
            'type' => 'required',
            'active' => 'required',
            'discounted_price' => ['nullable', new CheckNumberRule($discounted_price,'قیمت با تخفیف')],
            'url' => ['required', new CourseRule($id)],
        ];
    }
    public function messages()
    {
        return [
            'title.required'    => '  عنوان اجباری است.',
            'url.required'      => '  آدرس اجباری است',
            'price.required'    => '  قیمت اجباری است',
            'hours.required'    => '  ساعت اجباری است',
            'minutes.required'  => '  دقیقه اجباری است',
            'active.required'   => '  وضعیت اجباری است',
            'type.required'     => 'نوع دوره را انتخاب کنید',
        ];
    }
}
