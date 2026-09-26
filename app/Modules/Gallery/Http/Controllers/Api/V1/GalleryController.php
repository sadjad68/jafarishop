<?php

namespace App\Modules\Gallery\Http\Controllers\Api\V1;


use App\Modules\Comment\Http\Resources\CommentCollection;

use App\Modules\Service\Http\Resources\ServiceCollection;
use App\Modules\Blog\Http\Resources\BlogCategoryCollection;
use App\Modules\Blog\Http\Resources\BlogCollection;
use App\Modules\Gallery\Http\Resources\GalleryCategoryCollection;
use App\Modules\Gallery\Http\Resources\GalleryCollection;
use App\Modules\Gallery\Services\GalleryCategoryService;
use App\Modules\Gallery\Services\GalleryService;
use Illuminate\Http\JsonResponse;

class GalleryController

{
    public function __construct(
        GalleryCategoryService    $galleryCategoryService,
        GalleryService            $galleryService,
    )
    {
        $this->galleryCategoryService = $galleryCategoryService;
        $this->galleryService = $galleryService;

    }

    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function category(): JsonResponse
    {
        $found_categories = $this->galleryCategoryService->findAll();
        $galleryCategoryCollection = new GalleryCategoryCollection($found_categories);
        $gallery_categories = $galleryCategoryCollection->toArray(request());
        return response()->json([
            'data' => compact(
                'gallery_categories',
            ),
            'success' => true
        ]);
    }
    public function getList($url): JsonResponse
    {

        $found_gallery_category = $this->galleryCategoryService->findOne($url);
        $galleryCategoryCollection = new GalleryCategoryCollection($found_gallery_category);
        $gallery_category = $galleryCategoryCollection->transformItemToArray($found_gallery_category);
        $galleries = new GalleryCollection($found_gallery_category->galleries);
        return response()->json([
            'data' => compact(
                'galleries',
                'gallery_category'
            ),
            'success' => true
        ]);
    }


}
