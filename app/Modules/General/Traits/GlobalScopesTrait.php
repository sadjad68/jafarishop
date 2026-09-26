<?php
namespace App\Modules\General\Traits;

trait GlobalScopesTrait
{
    public function scopeFirstPage($query)
    {
        return $query->where('show_in_first_page', '1');
    }

    public function getFirstPageNameAttribute()
    {
        $name = $this->attributes['show_in_first_page'] == 1 ? 'نمایش در صفحه اول' : 'عدم نمایش در صفحه اول';
        return $name;
    }
}
