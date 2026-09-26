<?php

namespace App\Http\Controllers;

use App\Library\Assistant\Modules\V1\Blog;
use Illuminate\Routing\Controller;
use App\Modules\Blog\Services\BlogCategoryService;
use App\Modules\Blog\Services\BlogService;
use App\Modules\Page\Services\PageService;
use App\Modules\Service\Services\ServiceManager;

class PageController extends Controller
{
    public function detail($url)
    {

        $page = PageService::findOne($url);
        return view('pages.static-page.index',
            compact('page',
            ));

    }
}
