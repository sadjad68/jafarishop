<?php

namespace App\Modules\Tag\Http\Controllers\V1;

use App\Modules\Tag\Http\Resources\TagCollection;
use App\Modules\Tag\Services\TagService;
use App\Modules\Product\Http\Resources\PaginateProductCollection;
use Illuminate\Http\JsonResponse;

class TagController
{
    public function __construct(
        TagService $tagService,

    ) {
        $this->tagService = $tagService;

    }

    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function getIndex(): JsonResponse
    {
        $found_tag = $this->tagService->findAll();
        $tagCollection = new TagCollection($found_tag);
        $tagCollection->setHiddenFields(['description']);
        $tags = $tagCollection->toArray(request());

        return response()->json([
            'data' => compact(
                'tags',
            ),
            'success' => true,
        ]);
    }
    public function getDetail($url): JsonResponse
    {
        $found_tag = $this->tagService->findOne($url);
        $tagCollection = new TagCollection([$found_tag]);
        $tag = $tagCollection->transformItemToArray($found_tag);
        $perPage = request()->get('per_page', 12);
        $currentPage = request()->get('page');
        $paginate = $found_tag->products()->paginate($perPage, ['*'], 'page', $currentPage);
        $products = new PaginateProductCollection(@$paginate);
        return response()->json([
            'data' => compact(
                'tag',
                'products'
            ),
            'success' => true,
        ]);

    }

}
