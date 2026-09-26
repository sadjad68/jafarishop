<?php
namespace App\Modules\General\Traits;

trait UrlSetterTrait
{
    public function setUrlAttribute($value)
    {
        if (preg_match('/[\'^£$%&*()}{@#~?><>,|=:.\/]/', $value))
        {
            return redirect()->back()->with('error','از کاراکترهای اضافی (؟/[\'^£$%&*:()}{@#~?><>,|=]/)پرهیز کنید');
        }
//        $this->attributes['url'] = \Illuminate\Support\Str::slug($value);
        $this->attributes['url'] = trim(str_replace(' ', '-',$value));

    }


}
