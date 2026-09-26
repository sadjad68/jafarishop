<?php

namespace App\Modules\Comment\Http\Requests;

use App\Modules\Contact\Rules\MobileRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class AdminCommentRequest extends FormRequest
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
            'content' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'content.required'=>'  متن اجباری است.',
        ];
    }
}
