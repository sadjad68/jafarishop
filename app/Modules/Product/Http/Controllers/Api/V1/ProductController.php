<?php

namespace App\Modules\Product\Http\Controllers\Api\V1;


use App\Modules\Comment\Http\Resources\CommentCollection;
use App\Modules\Faq\Http\Resources\FaqCollection;
use App\Modules\Product\Http\Resources\PaginateProductCollection;
use App\Modules\Product\Http\Resources\SpecificationProductCollection;
use App\Modules\Setting\Http\Resources\SloganCollection;
use App\Modules\Setting\Services\SloganService;
use App\Modules\Tag\Http\Resources\TagCollection;
use App\Modules\Product\Http\Resources\BrandCollection;
use App\Modules\Product\Http\Resources\ImageCollection;
use App\Modules\Product\Http\Resources\ProductCategoryCollection;
use App\Modules\Product\Http\Resources\ProductCollection;
use App\Modules\Product\Http\Resources\ProductDetailCollection;
use App\Modules\Product\Http\Resources\PropertyCollection;
use App\Modules\Product\Http\Resources\VideoCollection;
use App\Modules\Product\Services\BrandService;
use App\Modules\Product\Services\ProductCategoryService;
use App\Modules\Product\Services\ProductService;
use App\Modules\Product\Http\Resources\SpecificationValueCollection;
use App\Modules\Product\Services\SpecificationService;
use Illuminate\Http\JsonResponse;


class ProductController

{
    public function __construct(
        ProductCategoryService $productCategoryService,
        ProductService         $productService,
        BrandService           $brandService,
        SpecificationService   $specificationService,
        ProductService         $ProductService,
        SloganService          $sloganService,
    )
    {
        $this->prodcutCategoryService = $productCategoryService;
        $this->prodcutService = $productService;
        $this->brandService = $brandService;
        $this->specificationService = $specificationService;
        $this->ProductService = $ProductService;
        $this->sloganService = $sloganService;
    }

    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function getProductDetail($url)
    {
        $found_product = $this->prodcutService->findOne($url);
        $productCollection = new ProductDetailCollection([$found_product]);
        $product = $productCollection->transformItemToArray($found_product);
        //categories
        $product_categories = $found_product->categories;
        $categoryCollection = new ProductCategoryCollection($product_categories);
        $categoryCollection->setHiddenFields(['description', 'title_seo', 'description_seo', 'noindex', 'product_counts']);
        $categories = $categoryCollection->toArray(request());
        //brand
        if ($found_product->brand) {
            $brandCollection = new BrandCollection($found_product->brand);
            $brandCollection->setHiddenFields(['description', 'title_seo', 'description_seo', 'noindex']);
            $brand = $brandCollection->transformItemToArray($found_product->brand);
        } else {
            $brand = null;
        }

        //slogans
        $found_slogans = $this->sloganService->findAll();
        $sloganCollection = new SloganCollection($found_slogans);
        $slogans = $sloganCollection->toArray(request());
        //specifications
        $found_specifications = $found_product->specifications->groupBy('parent_id');
        $specificationCollection = new SpecificationProductCollection($found_specifications);
        $specifications = $specificationCollection->toArray(request());
        $found_specification_values = $found_product->specification_vals->groupBy('specification_id');
        $specificationValueCollection = new SpecificationValueCollection($found_specification_values);
        $specification_values = $specificationValueCollection->toArray(request());
        //properties
        $product_properties = $found_product->properties;
        $propertyCollection = new PropertyCollection($product_properties);
        $properties = $propertyCollection->toArray(request());
        //faqs
        $product_faqs = $found_product->faqs;
        $faqCollection = new FaqCollection($product_faqs);
        $faqs = $faqCollection->toArray(request());
        //videos
        $product_videos = $found_product->videos;
        $videoCollection = new VideoCollection($product_videos);
        $videos = $videoCollection->toArray(request());
        //images
        $product_images = $found_product->images;

        $imageCollection = new ImageCollection($product_images);
        $images = $imageCollection->toArray(request());
        //comments
        $comments = new CommentCollection($found_product->comments);
        $rate = 0;

        if (count($comments) > 0) {
            $rate = ($found_product->comments->sum('rate') / count($found_product->comments));
        }
        //tags
        $tagCollection = new TagCollection($found_product->tags);
        $tagCollection->setHiddenFields(['description', 'image', 'title_seo', 'description_seo', 'noindex']);
        $tags = $tagCollection->toArray(request());

        //related
        if (count($found_product->related) > 0) {
            $productCollection = new ProductCollection($found_product->related->take(7));
            $productCollection->setHiddenFields(['description', 'vue_url']);
            $related = $productCollection->toArray(request());
        }else{
            $query = ['categories' => $product_categories->pluck('id')->toArray()];
            $category_products = $this->prodcutService->findAll($query, $found_product['id'], 7);
            $productCollection = new ProductCollection($category_products);
            $productCollection->setHiddenFields(['description', 'vue_url']);
            $related = $productCollection->toArray(request());
        }

        return response()->json([
            'data' => compact(
                'product',
                'categories',
                'comments',
                'brand',
                'slogans',
                'specification_values',
                'specifications',
                'properties',
                'faqs',
                'videos',
                'images',
                'related', 'rate',
                'tags'
            ),
            'success' => true,
        ]);
    }

    public function getDiscountedProductTest()
    {
        $query_timer = ['timer' => true,'paginate'=>true,'active'=>true];
        $found_timers =  $this->ProductService->findAll($query_timer);
        $perPage = request()->get('per_page', 12);
        $currentPage = request()->get('page');
        $paginate = $found_timers->paginate($perPage, ['*'], 'page', $currentPage);
        $products = new PaginateProductCollection(@$paginate);



        $query_timer1 = ['timer' => true,'active'=>true];
        $found_timers2 =  $this->ProductService->findAll($query_timer1);
        $productTimerCollection = new ProductCollection($found_timers2);
        $timer_products = $productTimerCollection->toArray(request());
        return response()->json([
            'data' => compact(
                'products',
                'timer_products',
            ),
            'success' => true,
        ]);
    }
}
