<?php

namespace App\Modules\Course\Http\Requests;

use Illuminate\Http\Request;
use App\Modules\General\Rules\CheckNumberRule;
use Illuminate\Foundation\Http\FormRequest;

class SessionRequest extends FormRequest
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
        $hours = $request->hours;
        $minutes = $request->minutes;
        return [
            'title' => 'required',
            'hours' => ['required', new CheckNumberRule($hours, 'ساعت')],
            'minutes' => ['required', new CheckNumberRule($minutes, 'دقیقه')],
        ];
    }
    public function messages()
    {
        return [
            'title.required'=>'  عنوان اجباری است.',
            'time.required'=>'  عنوان اجباری است.',
        ];
    }
}
