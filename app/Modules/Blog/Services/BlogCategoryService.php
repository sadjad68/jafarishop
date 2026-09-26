<?php
namespace App\Modules\Blog\Services;

use App\Modules\General\Helper\FileManager;
use App\Modules\Blog\DTO\BlogCategoryDTO;
use App\Modules\Blog\Entities\BlogCategory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;

class BlogCategoryService
{
    protected $model;

     public  function __construct(BlogCategory $model)
    {
        $this->model = $model;
    }

     public function create(BlogCategoryDTO $blogCategoryDTO)
    {
        $image = $blogCategoryDTO->getImage() ? FileManager::upload($blogCategoryDTO->getImage(),"blog-category") : null;
        $category = BlogCategory::create([
            'title' => $blogCategoryDTO->getTitle(),
            'url' => $blogCategoryDTO->getUrl(),
            'type' => $blogCategoryDTO->getType(),
            'parent_id' => $blogCategoryDTO->getParentId(),
            'description' => $blogCategoryDTO->getDescription(),
            'status' => $blogCategoryDTO->getStatus(),
            'image' => $image,
        ]);

    }
     public function update(int $id, BlogCategoryDTO $blogCategoryDTO)
    {
        $category = BlogCategory::findOrfail($id);
        $image = $blogCategoryDTO->getImage() ? FileManager::upload($blogCategoryDTO->getImage(),"blog-category") : $category->getRawOriginal('image');
        $category->update([
            'title' => $blogCategoryDTO->getTitle(),
            'url' => $blogCategoryDTO->getUrl(),
            'type' => $blogCategoryDTO->getType(),
            'description' => $blogCategoryDTO->getDescription(),
            'parent_id' => $blogCategoryDTO->getParentId(),
            'status' => $blogCategoryDTO->getStatus(),
            'image' => $image,
        ]);

    }
     public function destroy(int $id)
    {
        BlogCategory::destroy($id);
    }
    //
     public static function findAll($query = [], $except_id = null)
    {
        $blog_categories = BlogCategory::query();
        if ($except_id) {
            $blog_categories->where('id', '<>', $except_id);
        }
        if (isset($query['parent_id'])) {
            $blog_categories->where('parent_id',$query['parent_id']);
        }
        if (isset($query['layout'])) {
            $blog_categories->where('status',1);
        }
        if (isset($query['list'])) {
            $blog_categories->whereNull('parent_id');
        }


        $blog_categories = $blog_categories->orderby('id', 'DESC')->get();


        return $blog_categories;

    }
     public static function parents($query = [], $except_id = null)
    {
        $blog_categories = BlogCategory::query()->whereNull('parent_id');
        if ($except_id) {
            $blog_categories->where('id', '<>', $except_id);
        }


        $blog_categories = $blog_categories->orderby('id', 'DESC')->get();


        return $blog_categories;

    }
     public static function findOne($url)
    {
        return BlogCategory::where('url',$url)->firstOrFail();

    }
}
