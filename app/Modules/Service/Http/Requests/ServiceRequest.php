<?php

namespace App\Modules\Service\Http\Requests;

use App\Modules\General\Rules\UrlRule;
use App\Modules\Service\Entities\Service;
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

class ServiceRequest extends FormRequest
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
            'url' => ['required', new UrlRule($id, Service::class)],
            'parent_id' => [new ParentIdRule($id)],
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'عنوان اجباری است.',
            'url.required' => 'آدرس اجباری است',
        ];
    }
}
