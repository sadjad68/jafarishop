<?php

namespace App\Modules\Blog\Services\Api;

use App\Modules\Blog\DTO\Api\BlogCategoryDTO;
use App\Modules\Blog\Entities\BlogCategory;
use App\Modules\General\Services\ApiSeoService;

class BlogCategoryService
{

    public function list($request)
    {
        $query = BlogCategory::query();
        $page = @request()->get("page");

        if ($request->get("title")) {
            $query->where("title", 'like', "%" . $request->get("title") . "%");
        }
        return $query->orderBy("id", "desc")->offset(($page - 1) * 20)->limit(20)->select("id", 'url','parent_id','title')->get();
    }
    public function find($id)
    {
        $item = BlogCategory::findOrFail($id);
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
    public function create(BlogCategoryDTO $BlogCategoryDTO)
    {
        $input = $BlogCategoryDTO->toArray();
        $category =  BlogCategory::create($input);
        if (count(@$input['seo'] ?? [])) {
            ApiSeoService::set($category,@$input['seo'] ?? []);
        }
        return $this->showData($category);
    }

    public function update($id,BlogCategoryDTO $BlogCategoryDTO)
    {
        $category = BlogCategory::findOrFail($id);
        $input = $BlogCategoryDTO->toArray();
        $category->update($input);
        if (count(@$input['seo'] ?? [])) {
            ApiSeoService::set($category,@$input['seo'] ?? []);
        }
        return $this->showData($category);
    }
}
