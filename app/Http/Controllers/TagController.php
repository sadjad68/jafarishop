<?php

namespace App\Http\Controllers;

use App\Library\Assistant\Modules\V1\Tag;
use App\Modules\Product\Http\Resources\PaginateProductCollection;
use App\Modules\Tag\Http\Resources\TagCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller;
use App\Modules\Tag\Services\TagService;

class TagController extends Controller
{
    public function index()
    {
        $tags = TagService::findAll();
        return view('pages.tags.list', compact(
            'tags'
        ));
    }
    public function detail($url)
    {
        $tag = TagService::findOne($url);
        $products = $tag->products()->paginate(12);
        return view('pages.tags.detail', compact(['tag', 'products']));
    }
}
