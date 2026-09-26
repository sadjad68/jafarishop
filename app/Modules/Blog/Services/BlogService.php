<?php

namespace App\Modules\Blog\Services;

use App\Modules\General\Helper\FileManager;
use App\Modules\Blog\DTO\BlogDTO;
use App\Modules\Blog\Entities\Blog;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use App\Modules\General\Helper\FileUploader;

class BlogService
{
    protected $model;

    public function __construct(Blog $model)
    {
        $this->model = $model;
    }

    public function create(BlogDTO $blogDTO)
    {
        $image = null;
        if ($blogDTO->getImage()) {
            $uploader = new FileUploader($blogDTO->getImage(), "uploads/blog");
            $uploader->setExtensions(["jpeg", "webp", "png", "jpg"]);
            $uploader->setSizes(["big" => [450, 450], "small" => [100, 100]]);
            $image = $uploader->upload();
        }
        $blog = $this->model::create([
            'title' => $blogDTO->getTitle(),
            'url' => $blogDTO->getUrl(),
            'description' => $blogDTO->getDescription(),
            'parent_id' => $blogDTO->getParentId(),
            'image' => $image,
            'call_to_action' => $blogDTO->showCallToAction(),
            'author' => $blogDTO->getAuthor(),
            'publish_date' => $blogDTO->getPublishDate(),
            'show_in_first_page' => $blogDTO->showFirstPage(),
        ]);
        $blog->services()->attach($blogDTO->getServices());

    }

    public function update(int $id, BlogDTO $blogDTO)
    {
        $blog = $this->model::findOrfail($id);
        $image = $blog->getRawOriginal('image');
        if ($blogDTO->getImage()) {
            $uploader = new FileUploader($blogDTO->getImage(), "uploads/blog");
            $uploader->setExtensions(["jpeg", "webp", "png", "jpg"]);
            $uploader->setSizes(["big" => [450, 450], "small" => [100, 100]]);
            $image = $uploader->upload();
        }
        $blog->update([
            'title' => $blogDTO->getTitle(),
            'url' => $blogDTO->getUrl(),
            'description' => $blogDTO->getDescription(),
            'parent_id' => $blogDTO->getParentId(),
            'image' => $image,
            'call_to_action' => $blogDTO->showCallToAction(),
            'author' => $blogDTO->getAuthor(),
            'publish_date' => $blogDTO->getPublishDate(),
            'show_in_first_page' => $blogDTO->showFirstPage(),

        ]);
        $blog->services()->sync($blogDTO->getServices());

    }

    public function destroy(int $id)
    {

        Blog::destroy($id);
    }

    public static function findAll($query = [], $except_id = null,$limit = null)
    {
        $blogs = Blog::query();
        if ($except_id) {
            $blogs->where('id', '<>', $except_id);
        }
        if (isset($query['first_page'])) {
            $blogs->firstPage();
        }
        if (isset($query['category'])) {
            $blogs->where('parent_id', $query['specific_id']);
        }
        if (isset($query['service'])) {
            $blogs->whereHas('services', function ($query2) use ($query) {
                $query2->where("service_id", $query['specific_id']);
            });
        }
        if (isset($query['select'])) {

            $blogs->select($query['select']);
        }
        if (isset($query['sample'])) {
            $blogs->whereHas('services', function ($query2) use ($query) {
                $query2->whereIn("service_id", $query['specific_id']);
            });
        }
        if (isset($query['limit'])) {
            $blogs->take(intval($query['limit']));
        }
        if ($limit != null) {
        $blogs = $blogs->orderby('id', 'DESC')
            ->where("publish_date", "<", Carbon::tomorrow()
                ->timezone('Asia/Tehran')->format("Y-m-d"))->take($limit)->get();
        } else {
            $blogs = $blogs->orderby('id', 'DESC')
                ->where("publish_date", "<", Carbon::tomorrow()
                    ->timezone('Asia/Tehran')->format("Y-m-d"))->get();
        }
       return $blogs;

    }

    public static function findOne($url)
    {
        return Blog::where('url', $url)->where("publish_date", "<", Carbon::tomorrow()->timezone('Asia/Tehran')->format("Y-m-d"))->firstOrFail();

    }

    public static function updateView($id)
    {
        $blog = Blog::findOrfail($id);
        $blog->update([
            'view' => $blog['view'] + 1,
        ]);


    }
}
