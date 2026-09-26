<?php

namespace App\Http\Controllers;

use App\Library\SiteUrl;
use App\Modules\Blog\Entities\Blog;
use App\Modules\Blog\Entities\BlogCategory;
use App\Modules\Product\Entities\Product;
use App\Modules\Product\Entities\ProductCategory;
use Illuminate\Routing\Controller;

class LegacySiteRedirectController extends Controller
{
    public function productBySlug(string $url)
    {
        $product = Product::with('categories')->where('url', $url)->firstOrFail();

        return redirect(SiteUrl::product($product), 301);
    }

    public function productById(int $id)
    {
        $product = Product::with('categories')->findOrFail($id);

        return redirect(SiteUrl::product($product), 301);
    }

    public function categoryById(int $id)
    {
        $category = ProductCategory::query()->findOrFail($id);

        return redirect(SiteUrl::category($category), 301);
    }

    public function blogBySlug(string $url)
    {
        $blog = Blog::with('category')->where('url', $url)->firstOrFail();

        return redirect(SiteUrl::blog($blog), 301);
    }

    public function blogById(int $id)
    {
        $blog = Blog::with('category')->findOrFail($id);

        return redirect(SiteUrl::blog($blog), 301);
    }

    public function blogCategoryById(int $id)
    {
        $category = BlogCategory::query()->findOrFail($id);

        return redirect()->route('blog.list', ['url' => $category->url], 301);
    }
}
