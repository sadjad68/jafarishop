<?php

namespace App\Http\Controllers;

use App\Library\Assistant\Modules\V1\Gallery;
use Illuminate\Routing\Controller;
use App\Modules\Gallery\Services\GalleryCategoryService;

class GalleryController extends Controller
{
    public function category()
    {
        $gallery_categories = GalleryCategoryService::findAll();
        $gallery_categories->loadCount('galleries');
        return view('pages.gallery-cat.index', compact('gallery_categories'));
    }
    public function list($url)
    {

        $gallery_category = GalleryCategoryService::findOne($url);
        $galleries = $gallery_category->galleries;
        return view('pages.gallery-list.index', compact('galleries', 'gallery_category'));
    }

}
