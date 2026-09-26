<?php

namespace App\Modules\Product\Http\Requests\Api;

use App\Modules\General\Helper\NumberHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TimerRequest extends FormRequest
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
        $rules = [];

        if ($request->boolean('timer_active')) {
            $rules = [
                'end_timer' => 'required',
                'start_timer' => 'required',
                'start_hour' => [
                    'nullable',
                    'regex:/^((2[0-4]|1[0-9]|0?[0-9]|۲[۰-۴]|۱[۰-۹]|۰?[۰-۹])):([0-5][0-9]|[۰-۵][۰-۹])$/'
                ],
                'timer_hour' => [
                    'nullable',
                    'regex:/^((2[0-4]|1[0-9]|0?[0-9]|۲[۰-۴]|۱[۰-۹]|۰?[۰-۹])):([0-5][0-9]|[۰-۵][۰-۹])$/'
                ],
            ];
        }

        return $rules;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'start_hour' => NumberHelper::persian2LatinDigit($this->start_hour),
            'timer_hour' => NumberHelper::persian2LatinDigit($this->timer_hour)
        ]);
    }

    public function messages()
    {
        return [
            'end_timer.required'=>'  تاریخ انتها اجباری است.',
            'start_timer.required' => '  تاریخ شروع اجباری است',
            'start_hour.regex' => 'ساعت شروع نامعتبر است',
            'timer_hour.regex' => 'ساعت پایان نامعتبر است',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'خطای اعتبارسنجی در اطلاعات.',
            'errors' => $validator->errors(),
        ], 422));
    }
}
