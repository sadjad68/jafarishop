<?php

namespace App\Http\Controllers;

use App\Library\Assistant\Modules\V1\General;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\Blog\Entities\Blog;
use App\Modules\Product\Entities\Brand;
use App\Modules\Product\Entities\Product;
use App\Modules\Product\Entities\ProductCategory;
use App\Modules\Service\Entities\Service;
use App\Modules\Service\Entities\WorkSample;

class SearchController extends Controller
{
    public function detail(Request $request)
    {
        $search      = trim($request->get('search', ''));
        $search_form = $request->get('search_form');
        $perPage     = (int) $request->get('per_page', 12);
        $perPage     = in_array($perPage, [6, 12, 24, 48]) ? $perPage : 12;
        $activeTab   = $request->get('tab');
        $searchError = null;

        // حداقل ۳ کاراکتر الزامی است
        if ($search_form && mb_strlen($search) < 3) {
            $sortedResults = [];
            $searchError   = 'لطفاً حداقل ۳ کاراکتر برای جستجو وارد کنید.';
            return view('pages.search.index', compact(
                'search', 'sortedResults', 'activeTab', 'perPage', 'search_form', 'searchError'
            ));
        }

        $takeCount = $search_form != null ? null : 3;

        $results = [
            'categories' => [
                'data'  => $this->performSearch(ProductCategory::class, $search, $takeCount, ['id', 'title', 'url', 'image'], 'id', 'DESC', null, null, 24, 'categories_page'),
                'title' => 'دسته بندی محصولات',
                'view'  => 'pages.search._partials.categories',
            ],
            'brands' => [
                'data'  => $this->performSearch(Brand::class, $search, $takeCount, ['id', 'title', 'url', 'image'], 'id', 'DESC', null, null, $perPage, 'brands_page'),
                'title' => 'برند',
                'view'  => 'pages.search._partials.brands',
            ],
            'products' => [
                'data'  => $this->performSearch(Product::class, $search, $takeCount, ['id', 'title', 'url', 'image', 'price', 'final_price', 'discounted_price', 'stock', 'main_variant_specification_id'], 'stock', 'DESC', 'active', 1, $perPage, 'products_page'),
                'title' => 'محصولات',
                'view'  => 'pages.search._partials.products',
            ],
            'services' => [
                'data'  => $this->performSearch(Service::class, $search, $takeCount, ['id', 'title', 'url', 'image'], 'id', 'DESC', null, null, $perPage, 'services_page'),
                'title' => 'خدمات',
                'view'  => 'pages.search._partials.services',
            ],
            'portfolios' => [
                'data'  => $this->performSearch(WorkSample::class, $search, $takeCount, ['id', 'title', 'url'], 'id', 'DESC', null, null, $perPage, 'portfolios_page'),
                'title' => 'نمونه کار',
                'view'  => 'pages.search._partials.samples',
            ],
            'blogs' => [
                'data'  => $this->performSearch(Blog::class, $search, $takeCount, ['id', 'title', 'url', 'image', 'description', 'publish_date', 'view'], 'id', 'DESC', null, null, $perPage, 'blogs_page'),
                'title' => 'مطالب',
                'view'  => 'pages.search._partials.blogs',
            ],
        ];

        $sortedResults = collect($results)
            ->map(function ($item) {
                $data          = $item['data'];
                $item['count'] = method_exists($data, 'total') ? $data->total() : $data->count();
                return $item;
            })
            ->sortByDesc('count')
            ->toArray();

        if (!$activeTab || !array_key_exists($activeTab, $sortedResults)) {
            $activeTab = array_key_first($sortedResults);
        }

        return view('pages.search.index', compact(
            'search', 'sortedResults', 'activeTab', 'perPage', 'search_form', 'searchError'
        ));
    }


    private function performSearch(
        $model, $search, $takeCount,
        $select = ['*'], $orderByColumn = 'id', $orderDirection = 'DESC',
        $field = null, $value = null,
        $perPage = 12, $pageParam = 'page'
    ) {
        if ($model == Product::class) {
            $query = $model::whereSearch($search)
                ->where($field, $value)
                ->orderByRaw('stock > 0 DESC')
                ->orderByRaw('final_price > 0 DESC')
                ->orderBy('id', 'DESC')
                ->select($select);
        } elseif ($model == ProductCategory::class) {
            $query = $model::whereSearch($search)
                ->orderBy($orderByColumn, $orderDirection)
                ->where('show_in_site', 1)
                ->select($select);
        } else {
            $query = $model::whereSearch($search)
                ->orderBy($orderByColumn, $orderDirection)
                ->select($select);
        }

        if ($takeCount !== null) {
            return $query->take($takeCount)->get();
        }

        return $query->paginate($perPage, ['*'], $pageParam);
    }
}
