<?php

namespace App\Modules\Product\Http\Controllers\Api\V1;


use Illuminate\Support\Facades\DB;
use App\Modules\Product\Entities\Brand;
use App\Modules\Product\Entities\Product;
use App\Modules\Product\Entities\ProductCategory;
use App\Modules\Product\Http\Resources\BrandCollection;
use App\Modules\Product\Http\Resources\ListProductCollection;
use App\Modules\Product\Http\Resources\ProductCategoryCollection;
use App\Modules\Product\Http\Resources\ProductCollection;
use App\Modules\Product\Http\Resources\SpecificationCollection;
use App\Modules\Product\Services\BrandService;
use App\Modules\Product\Services\ProductCategoryService;
use App\Modules\Product\Services\ProductService;
use App\Modules\Product\Entities\Specification;
use App\Modules\Product\Services\SpecificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BrandController

{
    public function __construct(
        ProductCategoryService $productCategoryService,
        ProductService         $productService,
        BrandService           $brandService,
        SpecificationService   $specificationService,
    )
    {
        $this->productCategoryService = $productCategoryService;
        $this->productService = $productService;
        $this->brandService = $brandService;
        $this->specificationService = $specificationService;
    }

    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function getList(Request $request): JsonResponse
    {
        $brands = $this->brandService->findAll(['title'=>$request->get('title')], false);
        $brandCollection = new BrandCollection($brands);
        $brandCollection->setHiddenFields(['description']);
        $brands = $brandCollection->toArray(request());
        return response()->json([
            'data' => compact(
                'brands',
            ),
            'success' => true
        ]);
    }

    public function getProductList($url): JsonResponse
    {

        $found_brand = $this->brandService->findOne($url);
        $brandCollection = new BrandCollection($found_brand);

        $brand = $brandCollection->transformItemToArray($found_brand);
        //brands
        $brands = $this->brandService->findAll([], $found_brand['id']);


        //products
        $query = ['brand' => $found_brand['id'],'active'=>true];
        $brand_products = $this->productService->findAll($query, null, 12);
        $product_price = $this->productService->findAll($query)->load('categories');
        $productCollection = new ProductCollection($brand_products);
        $productCollection->setHiddenFields(['description', 'vue_url']);
        $products = $productCollection->toArray(request());
        $min_price = collect($product_price)->min('final_price');
        $max_price = collect($product_price)->max('final_price');
        //categories

        $category_ids = $product_price->pluck('categories.*.id')->flatten()->unique()->toArray();
        $query_category = ['filter_categories' => $category_ids];
        $categories = $this->productCategoryService->findAll($query_category, false);

        //specifications
        $query_filter = ['categories' => $category_ids, 'is_filter' => true];
        $found_filter = $this->specificationService->findAll($query_filter);
        $filterCollection = new SpecificationCollection($found_filter);
        $filterCollection->setHiddenFields(['type']);
        $filters = $filterCollection->toArray(request());

        return response()->json([
            'data' => compact(
                'brand',
                'brands',
                'products',
                'categories',
                'filters',
                'max_price',
                'min_price'
            ),
            'success' => true
        ]);
    }


    public function getProductListVue(Request $request): JsonResponse
    {
        $page = $request->input('page', 1);
        $query = Product::query()->active()->withCount('variants');
        if ($request->has('brand_id')) {
            $brand = Brand::findOrFail($request->get('brand_id'));

            $query->where("brand_id", $brand['id']);

            if ($request->get('category_id') != []) {
                $query->whereHas('categories', function ($query2) use ($request) {

                    $query2->where("product_category_id", $request->get('category_id'));
                });

            }

            if ($request->get('filter_id') != []) {
                $spfs = Specification::whereIn('id',$request->get('filter_id'))->get()->groupBy('parent_id');
                foreach ($spfs as $spf){
                    $query->whereHas('specifications', function ($query2) use ($spf) {
                        $query2->whereIn("specification_id", $spf->pluck('id'));
                    });
                }
            }
            if ($request->get('product_title') != null) {
                $query->where('title', 'LIKE', '%' . $request->get('product_title') . '%');
            }
            if ($request->get('discounted_price') == 1) {
                $query
                    ->whereRaw('CAST(discounted_price AS SIGNED) != 0')->where('stock','>',0);
            }

            if ($request->get('stock') == 1) {
                $query->where('stock','>',0);
            }


            if ($request->get('price_range') != null) {
                $minPrice = floatval($request->get('min_price')); // تبدیل به عدد شناور برای حداقل قیمت
                $maxPrice = floatval($request->get('max_price')); // تبدیل به عدد شناور برای حداکثر قیمت
                $query->where('final_price','>',0)->whereBetween(DB::raw('final_price * 1'), [$minPrice, $maxPrice]);
//                $query->whereRaw('CAST(final_price AS DECIMAL(10, 2)) BETWEEN ? AND ?', [$minPrice, $maxPrice]);
            }
            if ($request->get('sort_by') != null) {
                if ($request->get('sort_by') == "expensive") {
                    $category_products = $query->where('final_price','>',0)->where('stock','>',0)
                        ->orderByRaw('final_price * 1 DESC')
                        ->paginate(12, ['*'], 'page', $page);
                }
                if ($request->get('sort_by') == "cheapest") {
                    $category_products = $query->where('final_price','>',0)->where('stock','>',0)
                        ->orderByRaw('final_price * 1 ASC')
                        ->paginate(12, ['*'], 'page', $page);
                }
                if ($request->get('sort_by') == "newest") {
                    $category_products = $query->orderByDesc('id')->paginate(12, ['*'], 'page', $page);
                }
            } else {
                $category_products = $query->orderByRaw('stock > 0 DESC')
                    ->orderByRaw('final_price > 0 DESC')
                    ->orderBy('id', 'DESC')->paginate(12, ['*'], 'page', $page);

            }


            $productCollection = new ListProductCollection($category_products);
            $products = $productCollection->toArray(request());
            $lastPage = $category_products->lastPage();
            $currentPage = $category_products->currentPage();

            return response()->json([
                'data' => compact(
                    'products',
                    'lastPage',
                    'currentPage',
                ),
                'success' => true
            ]);
        }

    }

}
