<?php

namespace App\Modules\Service\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FeeRequest extends FormRequest
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

    public function rules()
    {
        switch ($this->segment(3)) {
            case 'create' :
                return [
                    'minimum_price' => 'required|array',
                    'maximum_price' => 'required|array',
                    'service_id' => 'required|array',
                ];
                break;
            case 'edit' :
                return [
                    'minimum_price' => 'required',
                    'maximum_price' => 'required',
                    'service_id' => 'required',

                ];
                break;
        }
    }
    public function messages()
    {
        return [
            'minimum_price.required' => 'کمترین نرخ اجباری است.',
            'maximum_price.required' => '  بیشترین نرخ اجباری است',
            'service_id.required' => '  انتخاب خدمات اجباری است',
            'minimum_price.array' => 'کمترین نرخ باید آرایه باشد.',
            'maximum_price.array' => 'بیشترین نرخ باید آرایه باشد.',
            'service_id.array' => 'خدمات باید آرایه باشد.',

        ];
    }
}
