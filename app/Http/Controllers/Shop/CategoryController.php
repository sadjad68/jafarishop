<?php

namespace App\Http\Controllers\Shop;

use Illuminate\Routing\Controller;
use App\Modules\Product\Services\BrandService;
use App\Modules\Product\Services\ProductCategoryService;
use App\Modules\Product\Services\ProductService;
use App\Modules\Product\Services\SpecificationService;

class CategoryController extends Controller
{
    public function list()
    {
        $query_category = ['list' => true];
        $product_categories = ProductCategoryService::findAll($query_category, false);
        return view('pages.category-product.index', compact('product_categories'));
    }

    public function detail($url)
    {
        $product_category = ProductCategoryService::findOne($url);

        $children = $product_category->children()->select('id', 'title', 'url', 'parent_id', 'image')->get();
        //products
        $category_ids = [];



        $category_ids = ProductCategoryService::getAllCategoryIdsRecursive($product_category);
        $query = [
            'category_filter' => $product_category,
            'page_stock' => true,
            'active'=>true,
            'select'=> ['id','title','url','image','price','price', 'discounted_price', 'final_price','stock']
        ];

        // بازه قیمتی انتخاب‌شده کاربر (در اولین لود از URL) تا لیست اولیه هم فیلتر شود
        if (\request()->priceRange) {
            $parts = array_map('intval', explode('_', \request()->priceRange));
            if (count($parts) === 2) {
                $query['min_price'] = $parts[0];
                $query['max_price'] = $parts[1];
            }
        }

        $products = ProductService::findAll($query, null, 12);
        if ($product_category->have_price_range == 1) {
            $min_price = $product_category->min_price;
            $max_price = $product_category->max_price;
        } else {
            $pricesQuery = ['categories' => $category_ids];
            $min_price = ProductService::prices($pricesQuery)['min_price'];
            $max_price = ProductService::prices($pricesQuery)['max_price'];
        }
        //brands
        $query_brand = ['has_products' => true,'brand_categories'=>$category_ids,'select'=>['id','title']];
        $brands = BrandService::findAll($query_brand);
        //specifications
        $query_filter = ['categories' => $category_ids, 'is_filter' => true];
        $filters = SpecificationService::findAll($query_filter);

        $min_filter_price = \request()->priceRange ? intval(explode('_', \request()->priceRange)[0]) : $min_price;
        $max_filter_price = \request()->priceRange ? intval(explode('_', \request()->priceRange)[1]) : $max_price;
        return view('pages.product-list.index', compact(['product_category', 'children',
            'products', 'brands', 'filters', 'max_price', 'min_price', 'max_filter_price', 'min_filter_price']));
    }

}
