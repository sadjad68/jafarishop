<?php

namespace App\Modules\Blog\Services\Api;

use App\Modules\Blog\DTO\BlogDTO;
use App\Modules\Blog\Entities\Blog;
use App\Modules\General\Helper\FileUploader;
use App\Modules\General\Services\ApiSeoService;

class BlogService
{

    public function list($request)
    {
        $query = Blog::query();
        $page = @request()->get("page");

        if ($request->get("title")) {
            $query->where("title", 'like', "%" . $request->get("title") . "%");
        }
        return $query->orderBy("id", "desc")->offset(($page - 1) * 20)->limit(20)->select("id", 'url','parent_id','title')->get();
    }
    public function find($id)
    {
        $item = Blog::findOrFail($id);
        return $this->showData($item);
    }

    public function showData($item){

        $data = $item->toArray();

        unset($data['title_seo']);
        unset($data['description_seo']);

        $data["seo"]['h1'] = @$item->seo->h1;
        $data["seo"]['title_seo'] = @$item->seo->title_seo;
        $data["seo"]['description_seo'] = @$item->seo->description_seo;
        $data["seo"]['noindex'] = @$item->seo->noindex;

        return $data;
    }

    public function create(BlogDTO $blogDTO)
    {
        $image = null;
        if ($blogDTO->getImage()) {
            $file = $blogDTO->getImage();
            $uploader = new FileUploader($file, "uploads/blog");
            $uploader->setExtensions(["jpeg", "webp", "png", "jpg"]);
            $uploader->setSizes([
                "big" => [450, 450],
                "small" => [100, 100]
            ]);
            $image = $uploader->upload();
        }

        $blog = Blog::create([
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

        return $this->showData($blog);
    }

    public function update(int $id, BlogDTO $blogDTO)
    {
        $blog = Blog::findOrfail($id);
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
        return $this->showData($blog);

    }
}
