<?php

namespace App\Modules\General\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Searchable
{
    /**
     * جستجوی پیشرفته بر اساس کلمات کلیدی
     *
     * @param Builder $query
     * @param string $keyword
     * @return Builder
     */
    public function scopeWhereSearch(Builder $query, $keyword)
    {
        // حذف فاصله‌های اضافی و تبدیل رشته به آرایه کلمات کلیدی
        $keywords = explode(' ', strtolower(trim($keyword)));

        return $query->where(function ($q) use ($keywords) {
            foreach ($keywords as $word) {
                // بررسی تطابق بخشی از هر کلمه
                $q->whereRaw(
                    "REPLACE(REPLACE(LOWER(title), ' ', ''), '_', '') LIKE ?",
                    ["%{$word}%"]
                );
            }
        });
    }
}
