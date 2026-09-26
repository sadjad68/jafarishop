<?php

namespace App\Http\Controllers\Shop;

use App\Library\Assistant\Modules\V1\Shop;
use App\Modules\Product\Entities\Brand;
use Illuminate\Routing\Controller;
use App\Modules\Product\Services\BrandService;
use App\Modules\Product\Services\ProductCategoryService;
use App\Modules\Product\Services\ProductService;
use App\Modules\Product\Services\SpecificationService;

class BrandController extends Controller
{
    public function list()
    {
        $brands = BrandService::findAll();
        return view('pages.brand-list.index', compact('brands'));
    }

    public function detail($url)
    {
        if (ctype_digit((string) $url)) {
            $brand = Brand::query()->findOrFail($url);
        } else {
            $brand = BrandService::findOne($url);
        }
        $brands = BrandService::findAll(['select'=>['id','title','url']], $brand['id']);

        $query = ['brand' => $brand['id'],'page_stock' => true,'active'=>true];
        $products = ProductService::findAll($query, null, 12);
        $min_price = ProductService::prices()['min_price'];
        $max_price = ProductService::prices()['max_price'];

        //categories
        $query_category = ['has_products' => true,'category_brand'=>$brand->id,'select'=>['id','title']];
        $categories = ProductCategoryService::findAll($query_category, false);
        //specifications
        $query_filter = ['categories' => $categories->pluck('id'), 'is_filter' => true];
        $filters = SpecificationService::findAll($query_filter);

        $min_filter_price = \request()->priceRange ? intval(explode('_', \request()->priceRange)[0]) : $min_price;
        $max_filter_price = \request()->priceRange ? intval(explode('_', \request()->priceRange)[1]) : $max_price;
        return view('pages.brand-detail.index', compact(['brand', 'categories',
            'products', 'brands', 'filters', 'max_price', 'min_price', 'max_filter_price', 'min_filter_price']));
    }

}
