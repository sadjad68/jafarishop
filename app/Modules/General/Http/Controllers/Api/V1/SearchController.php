<?php

namespace App\Modules\General\Http\Controllers\Api\V1;

use App\Modules\Service\Entities\Service;
use App\Modules\Service\Http\Resources\LayoutServiceCollection;
use App\Modules\Service\Http\Resources\SearchedServiceCollection;
use App\Modules\Blog\Entities\Blog;
use App\Modules\Blog\Http\Resources\BlogCollection;
use App\Modules\Blog\Http\Resources\SearchedBlogCollection;
use App\Modules\Service\Entities\WorkSample;
use App\Modules\Service\Http\Resources\FirstPageWorkSampleCollection;
use App\Modules\Service\Http\Resources\SearchedWorkSampleCollection;
use App\Modules\Product\Entities\Brand;
use App\Modules\Product\Entities\Product;
use App\Modules\Product\Entities\ProductCategory;
use App\Modules\Product\Http\Resources\ProductCollection;
use App\Modules\Product\Http\Resources\SearchedBrandCollection;
use App\Modules\Product\Http\Resources\SearchedCategoryCollection;
use App\Modules\Product\Http\Resources\SearchedProductCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController
{

    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function getData(Request $request): JsonResponse
    {

        $search = $request->get('search');
        $takeCount = $request->get('search_form') != null ? null : 3;
        $blogs = $this->performSearch(Blog::class, $search, $takeCount);
        $portfolios = $this->performSearch(WorkSample::class, $search, $takeCount);
        $services = $this->performSearch(Service::class, $search, $takeCount);
        $brands = $this->performSearch(Brand::class, $search, $takeCount, ['id', 'title', 'url']);
        $categories = $this->performSearch(ProductCategory::class, $search, $takeCount, ['id', 'title', 'url']);
        $products = $this->performSearch(Product::class, $search, $takeCount, ['id', 'title', 'url', 'image', 'price', 'final_price', 'discounted_price'], 'active', 1, $takeCount == null ? 6 : $takeCount);
        $searched_products = $request->get('search_form') != null ? new ProductCollection(@$products) : new SearchedProductCollection(@$products);

        $searched_services = $request->get('search_form') != null ? new LayoutServiceCollection(@$services) : new SearchedServiceCollection(@$services);
        $searched_blogs = $request->get('search_form') != null ? new BlogCollection(@$blogs) : new SearchedBlogCollection(@$blogs);
        $searched_portfolios = $request->get('search_form') != null ? new FirstPageWorkSampleCollection(@$portfolios) : new SearchedWorkSampleCollection(@$portfolios);

        $searched_brands = new SearchedBrandCollection(@$brands);
        $searched_categories = new SearchedCategoryCollection(@$categories);

        return response()->json([
            'data' => compact(
                'searched_blogs',
                'searched_portfolios',
                'searched_services',
                'searched_brands',
                'searched_categories',
                'searched_products'
            ),
            'success' => true,
        ]);
    }

    private function performSearch($model, $search, $takeCount, $select = ['*'], $field = null, $value = null)
    {
        $query = $model::whereSearch($search)
            ->where($field, $value)
            ->orderBy('id', 'DESC')
            ->select($select);

        if ($takeCount !== null) {
            $query->take($takeCount);
        }

        return $query->get();
    }

}
