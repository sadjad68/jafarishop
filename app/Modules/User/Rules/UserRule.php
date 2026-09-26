<?php
namespace App\Modules\User\Rules;
use Illuminate\Contracts\Validation\Rule;

class UserRule implements Rule
{
    public function passes($attribute, $value)
    {
        // Your validation logic goes here
        // You can access the attribute and its value for validation

        // return $value  ;
    }

    public function message()
    {
        return 'The specific rule has failed.';
    }
}
