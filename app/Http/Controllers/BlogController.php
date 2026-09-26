<?php

namespace App\Http\Controllers;

use App\Library\Assistant\Modules\V1\Blog;
use Illuminate\Routing\Controller;
use App\Modules\Blog\Entities\BlogCategory;
use App\Modules\Blog\Services\BlogCategoryService;
use App\Modules\Blog\Services\BlogService;
use App\Modules\Service\Services\ServiceManager;

class BlogController extends Controller
{
    public function index()
    {
        $blog_categories = BlogCategoryService::findAll(['list' => 1])
            ->reject(function ($category) {
                return $category->type === BlogCategory::TYPE_VIDEO;
            })
            ->values();
        $textChildren = function ($query) {
            $query->where('type', BlogCategory::TYPE_TEXT);
        };
        $blog_categories->loadCount(['children' => $textChildren, 'blogs']);
        $blog_categories->load(['children' => $textChildren]);
        $blogs = [];
        return view('pages.blog-category.index', compact(
            'blog_categories', 'blogs'
        ));
    }

    public function videos()
    {
        $blog_category = BlogCategory::query()
            ->where('type', BlogCategory::TYPE_VIDEO)
            ->first();
        if (!$blog_category) {
            abort(404);
        }

        return $this->list($blog_category->url);
    }


    public function list($url)
    {
        $blog_category = BlogCategoryService::findOne($url);

            $query = ['parent_id' => $blog_category['id']];
            $blog_categories = BlogCategoryService::findAll($query);
            $query2 = ['specific_id' => $blog_category['id'], 'category' => true];
            $blogs = BlogService::findAll($query2);
            return view('pages.blog-list.index', compact(
                'blog_categories', 'blog_category', 'blogs'
            ));


    }

    public function show($category, $url)
    {
        return $this->detail($url);
    }

    public function listById(int $id)
    {
        $category = BlogCategory::query()->findOrFail($id);
        if ($category->type === BlogCategory::TYPE_VIDEO) {
            return redirect()->route('blog.videos', [], 301);
        }

        return $this->list($category->url);
    }

    public function showById(int $id)
    {
        $blog = \App\Modules\Blog\Entities\Blog::with('category')->findOrFail($id);
        if ($blog->category && $blog->category->type === BlogCategory::TYPE_VIDEO) {
            return redirect()->route('blog.video', ['url' => $blog->url], 301);
        }

        return $this->detail($blog->url);
    }

    public function detail($url)
    {

        $blog = BlogService::findOne($url);
        //update_view
        $blog->timestamps = false;
        $blog->increment('view');
        $blog->timestamps = true;
        //services
        $query = ['blog' => true, 'specific_id' => $blog['id']];
        $services = ServiceManager::findAll($query, false);
        //related_blogs
        $query2 = ['category' => true, 'specific_id' => $blog['parent_id'],'limit'=>12];
        $related_blogs = BlogService::findAll($query2, $blog->id);
        //comments
        $comments = $blog->comments;
        return view('pages.blog-detail.index',
            compact('blog', 'services', 'related_blogs',
                'comments'
            ));

    }
}
