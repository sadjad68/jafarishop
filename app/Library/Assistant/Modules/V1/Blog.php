<?php

namespace App\Library\Assistant\Modules\V1;

use App\Library\Assistant\Core\API;
use Illuminate\Support\Collection;

class Blog
{
    public static function getBlogList(): Collection
    {
        return API::get('v1/blogs');
    }
    public static function getBlogCategoryList($url): Collection
    {
        return API::get('v1/blogs/' . $url);
    }

    public static function getBlogDetail($url): Collection
    {
        return API::get('v1/blog/' . $url);
    }
}
