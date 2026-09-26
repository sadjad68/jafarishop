<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Modules\Banner\Services\BannerService;
use App\Modules\Banner\Services\HighlightService;
use App\Modules\Blog\Services\BlogService;
use App\Modules\Certification\Services\CertificateService;
use App\Modules\Gallery\Services\GalleryService;
use App\Modules\Location\Entities\City;
use App\Modules\Location\Entities\State;
use App\Modules\Product\Services\BrandService;
use App\Modules\Product\Services\ProductCategoryService;
use App\Modules\Product\Services\ProductService;
use App\Modules\Service\Services\PackageService;
use App\Modules\Service\Services\ServiceManager;
use App\Modules\Service\Services\WorkSampleService;
use App\Modules\Tag\Services\TagService;
use App\Modules\User\Services\UserService;
use Illuminate\Support\Facades\File;
class FirstPageController extends Controller
{
    /**
     * @return array{
     *     highlightData: array,
     *     tagHighlightMap: array,
     *     firstPageBannerRows: list<array>,
     *     firstPageBottomSlots: list<array>,
     *     firstPageEndBannerRows: list<array>
     * }
     */
    protected function firstPageHighlightViewData(): array
    {
        $highlights = HighlightService::findAll(['first_page' => true], 100);
        $split = HighlightService::splitForFirstPage($highlights);

        return [
            'highlightData' => $split['placeMap'],
            'tagHighlightMap' => $split['tagMap'],
            'firstPageBannerRows' => HighlightService::firstPageMainBannerRowDefinitions(),
            'firstPageBottomSlots' => HighlightService::firstPageBottomBannerSlotDefinitions(),
            'firstPageEndBannerRows' => HighlightService::firstPageEndBannerRowDefinitions(),
        ];
    }

    public function theme1()
    {

        $query = ['first_page' => true];
        $services = ServiceManager::findAll($query, false);
        $banners = BannerService::findAll($query);
        $samples = WorkSampleService::findAll($query, 10);
        $galleries = GalleryService::findAll($query,6);
        $team_members = UserService::findAll($query);
        $certificates = CertificateService::findAll($query);
        $packages = PackageService::findAll($query, 3);
        $query_active = ['first_page' => true,'active'=>true];
        $products = ProductService::findAll($query_active,null,12);
        $product_categories = ProductCategoryService::findAll($query,false,null,10);
        $query_timer = ['timer' => true,'active'=>true];
        $timer_products = ProductService::findAll($query_timer,null,12);
        $tags = TagService::findAll($query);
        $blogs = BlogService::findAll($query,null,3);

        return view('pages.first-page.theme1.index', compact(
            ['banners', 'services', 'samples', 'galleries'
                , 'team_members', 'certificates', 'packages', 'products', 'product_categories', 'timer_products',
                'tags','blogs',
            ]));
    }

    public function theme2()
    {
        $query = ['first_page' => true];
        $services = ServiceManager::findAll($query, false);
        $banners = BannerService::findAll($query);
        $query_active = ['first_page' => true,'active'=>true];
        $products = ProductService::findAll($query_active,null,12);
        $home_product_tabs = [];
        $home_product_root_ids = [];
        if ($products && count($products) > 0) {
            [$home_product_tabs, $home_product_root_ids] = $this->buildHomeProductCategoryTabs($products);
        }
        $product_categories = ProductCategoryService::findAll(['first_page'=>true,'select'=>['id','title','url','parent_id','sort','image']],false,null,18);
        $query_timer = ['timer' => true,'active'=>true];
        $timer_products = ProductService::findAll($query_timer);
        $tags = TagService::findAll($query,null,4);
        $blogs = BlogService::findAll($query,null,3);
        $brands = BrandService::findAll($query,null,12);
        return view('pages.first-page.theme2.index', array_merge(
            compact(
                [
                    'banners', 'services',
                    'products', 'product_categories', 'timer_products',
                    'tags', 'blogs', 'brands', 'home_product_tabs', 'home_product_root_ids',
                ]
            ),
            $this->firstPageHighlightViewData()
        ));
    }

    private function buildHomeProductCategoryTabs($products)
    {
        $products->load([
            'categories' => function ($query) {
                $query->select('product_categories.id', 'product_categories.title', 'product_categories.parent_id', 'product_categories.sort');
            },
            'categories.parent' => function ($query) {
                $query->select('id', 'title', 'parent_id', 'sort');
            },
            'categories.parent.parent' => function ($query) {
                $query->select('id', 'title', 'parent_id', 'sort');
            },
            'categories.parent.parent.parent' => function ($query) {
                $query->select('id', 'title', 'parent_id', 'sort');
            },
        ]);

        $tabs = [];
        $rootIdsByProduct = [];

        foreach ($products as $product) {
            $rootIds = [];
            foreach ($product->categories as $category) {
                $root = $this->resolveRootProductCategory($category);
                if (!$root) {
                    continue;
                }
                $rootId = (int) $root->id;
                $rootIds[] = $rootId;
                $tabs[$rootId] = [
                    'id' => $rootId,
                    'title' => $root->title,
                    'sort' => (int) ($root->sort ?? 0),
                ];
            }
            $rootIdsByProduct[$product->id] = array_values(array_unique($rootIds));
        }

        uasort($tabs, function ($a, $b) {
            if ($a['sort'] === $b['sort']) {
                return $a['id'] <=> $b['id'];
            }
            return $a['sort'] <=> $b['sort'];
        });

        return [array_values($tabs), $rootIdsByProduct];
    }

    private function resolveRootProductCategory($category)
    {
        $current = $category;
        $depth = 0;
        while ($current && $current->parent_id && $depth < 12) {
            $parent = $current->relationLoaded('parent') ? $current->parent : null;
            if (!$parent) {
                break;
            }
            $current = $parent;
            $depth++;
        }
        return $current;
    }
}
