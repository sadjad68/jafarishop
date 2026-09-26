<?php

namespace App\Http\Controllers;

use App\Library\Assistant\Modules\V1\Blog;
use Illuminate\Routing\Controller;
use App\Modules\Blog\Services\BlogCategoryService;
use App\Modules\Blog\Services\BlogService;
use App\Modules\Gallery\Services\GalleryCategoryService;
use App\Modules\Page\Services\PageService;
use App\Modules\Product\Entities\Product;
use App\Modules\Product\Services\BrandService;
use App\Modules\Product\Services\ProductCategoryService;
use App\Modules\Product\Services\ProductService;
use App\Modules\Service\Services\PackageService;
use App\Modules\Service\Services\ServiceManager;
use App\Modules\Service\Services\WorkSampleService;
use App\Modules\Setting\Services\SitemapService;
use App\Modules\Tag\Services\TagService;

class SitemapController extends Controller
{
//    public function index()
//    {
//        $sitemaps = SitemapService::findAll(['show' => 1,'select'=>'url']);
//        $services = ServiceManager::findAll(['select'=>'url'],false);
//        $blog_categories = BlogCategoryService::findAll(['select'=>'url']);
//        $blogs = BlogService::findAll(['select'=>'url']);
//        $samples = WorkSampleService::findAll(['with_url'=>1,'select'=>'url']);
//        $gallery_categories = GalleryCategoryService::findAll(['select'=>'url']);
//        $packages = PackageService::findAll(['select'=>'url']);
//        $product_categories = ProductCategoryService::findAll(['select'=>'url'], false);
//        $brands = BrandService::findAll(['select'=>'url']);
//        $products = ProductService::findAll(['active' => true,'select'=>'url']);
//        $tags = TagService::findAll(['select'=>'url']);
//        $pages = PageService::findAll(['select'=>'url']);
//        return response()->view('pages.site-map.index', [
//            'sitemaps' => $sitemaps,
//            'services' => $services,
//            'blog_categories' => $blog_categories,
//            'samples'=>$samples,
//            'gallery_categories'=>$gallery_categories,
//            'packages'=>$packages,
//            'product_categories'=>$product_categories,
//            'brands'=>$brands,
//            'products'=>$products,
//            'tags'=>$tags,
//            'blogs'=>$blogs,
//            'pages'=>$pages,
//        ])->header('Content-Type', 'text/xml');
//    }

    public function index()
    {
        $services = ServiceManager::findAll(['select' => 'url'], false);
        $blogs = BlogService::findAll(['select' => 'url']);
        $blog_categories = BlogCategoryService::findAll(['select' => 'url']);
        $products = ProductService::findAll(['active' => true,'select'=>'url']);
        $product_categories = ProductCategoryService::findAllSiteMap(['select' => 'url']);
        $brands = BrandService::findAll(['select' => 'url']);
        $tags = TagService::findAll(['select' => 'url']);
        $pages = PageService::findAll(['select' => 'url']);
        return response()->view('pages.site-map.index', compact('services', 'blogs', 'blog_categories', 'product_categories', 'brands', 'tags', 'pages', 'products'))->header('Content-Type', 'text/xml');
    }

    public function staticPages()
    {
        $sitemaps = SitemapService::findAll(['show' => 1, 'select' => 'url']);
        return response()->view('pages.site-map.partials.static-pages', compact('sitemaps'))->header('Content-Type', 'text/xml');
    }

    public function services()
    {
        $services = ServiceManager::findAll(['select' => 'url'], false);
        return response()->view('pages.site-map.partials.services', compact('services'))->header('Content-Type', 'text/xml');
    }

    public function blogs()
    {
        $blogs = BlogService::findAll(['select' => 'url']);
        return response()->view('pages.site-map.partials.blogs', compact('blogs'))->header('Content-Type', 'text/xml');
    }

    public function blogCategories()
    {
        $blog_categories = BlogCategoryService::findAll(['select' => 'url']);
        return response()->view('pages.site-map.partials.blog-categories', compact('blog_categories'))->header('Content-Type', 'text/xml');
    }

    public function productChunk($chunk)
    {
        $limit = 2000;
        $products = Product::query()->active()->select('url')->skip(($chunk - 1) * $limit)->take($limit)->get();
        return response()->view('pages.site-map.partials.products-chunk', compact('products'))->header('Content-Type', 'text/xml');
    }

    public function productCategories()
    {
        $product_categories = ProductCategoryService::findAllSiteMap(['select' => 'url']);
        return response()->view('pages.site-map.partials.product-categories', compact('product_categories'))->header('Content-Type', 'text/xml');
    }

    public function brands()
    {
        $brands = BrandService::findAll(['select' => 'url']);
        return response()->view('pages.site-map.partials.brands', compact('brands'))->header('Content-Type', 'text/xml');
    }

    public function tags()
    {
        $tags = TagService::findAll(['select' => 'url']);
        return response()->view('pages.site-map.partials.tags', compact('tags'))->header('Content-Type', 'text/xml');
    }

    public function pages()
    {
        $pages = PageService::findAll(['select' => 'url']);
        return response()->view('pages.site-map.partials.pages', compact('pages'))->header('Content-Type', 'text/xml');
    }

}
