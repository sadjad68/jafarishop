<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Modules\General\Helper\NumberHelper;
use App\Modules\Location\Entities\Address;
use App\Modules\Location\Entities\City;
use App\Modules\Location\Entities\State;
use App\Modules\Location\Http\Resources\AddressCollection;
use App\Modules\Location\Services\AddressService;
use App\Modules\Location\Services\CityService;
use App\Modules\Location\Services\StateService;
use App\Modules\Order\Http\Resources\BasketCollection;
use App\Modules\Order\Services\BasketService;
use App\Modules\Product\Entities\Product;
use App\Modules\Product\Http\Resources\PaginateProductCollection;
use App\Modules\Product\Services\ProductService;
use App\Modules\Product\Services\SpecificationService;


class ApiCallController extends Controller
{
    public function emallProduct(Request $request)
    {
        $page = $request->get('page', 1);
        $per_page = $request->get('item_per_page', 10);
        $products = ProductService::findPaginate($per_page, $page);
        $product = ProductService::findAll(['active' => true]);
        return response()->json([
            'products' => $products->items(),
            'total_items' => count($product),
            'pages_count' => $products->lastPage(),
            'item_per_page' => intval($request->item_per_page) ? intval($request->item_per_page) : count($products),
            'page_num' => intval($page),
        ]);
    }

    public function zarebinProduct(Request $request)
    {
        $page = $request->get('page', 1);
        $per_page = $request->get('item_per_page', 20);

        $products = ProductService::getFormattedZarebinProducts($per_page, $page);

        return response()->json([
            'total_items' => $products->total(),
            'pages_count' => $products->lastPage(),
            'products' => $products->items(),

        ]);
    }

    public function storeyab(Request $request)
    {
        $perPage = 100;

        $products = Product::query()
            ->active()
            ->with(['category', 'images'])
            ->paginate($perPage);

        return response()->json([
            'page_count' => $products->lastPage(),
            'products' => $products->getCollection()->map(function ($product) {
                return [
                    'title' => mb_substr($product->title, 0, 150),
                    'old_price' => $product->discounted_price ? number_format($product->price) : null,
                    'off_percent' => $product->percent,
                    'price' => $product->stock > 0 ? number_format($product->final_price ?? $product->price) : null,
                    'image_link' => $product->getImage(),
                    'short_desc' => mb_substr(strip_tags($product->description ?? $product->title), 0, 120),
                    'page_url' => \App\Library\SiteUrl::product($product),
                    'status' => $product->stock > 0 ? 'instock' : 'outofstock',
                    'complete_desc' => mb_substr(strip_tags($product->description ?? ''), 0, 1000),
                    'category_name' => $product->categoryForStoryab->pluck('title')->values()->toArray(),
                ];
            })->values()
        ]);
    }


}
