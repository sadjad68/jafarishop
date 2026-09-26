<?php

namespace App\Modules\Product\Services\Api;

use Illuminate\Http\UploadedFile;
use App\Modules\General\Helper\FileManager;
use App\Modules\General\Helper\FileUploader;
use App\Modules\General\Services\ApiSeoService;
use App\Modules\Product\DTO\Api\ProductCategoryDTO;
use App\Modules\Product\Entities\ProductCategory;

class ProductCategoryService
{

    public function list($request)
    {
        $query = ProductCategory::query();
        $page = @request()->get("page");

        if ($request->get("title")) {
            $query->where("title", 'like', "%" . $request->get("title") . "%");
        }
        return $query->orderBy("id", "desc")->offset(($page - 1) * 20)->limit(20)->select("id", 'url','parent_id','title')->get();
    }
    public function find($id)
    {
        $item = ProductCategory::findOrFail($id);
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
    public function create(ProductCategoryDTO $ProductCategoryDTO)
    {
        $input = $ProductCategoryDTO->toArray();
        $seo = $ProductCategoryDTO->getSeo();

        if ($image = $this->uploadImage($ProductCategoryDTO->getImage())) {
            $input['image'] = $image;
        }

        $category = ProductCategory::create($input);
        if (count($seo)) {
            ApiSeoService::set($category, $seo);
        }
        return $this->showData($category);
    }

    public function update($id,ProductCategoryDTO $ProductCategoryDTO)
    {
        $category = ProductCategory::findOrFail($id);
        $input = $ProductCategoryDTO->toArray();
        $seo = $ProductCategoryDTO->getSeo();

        if ($image = $this->uploadImage($ProductCategoryDTO->getImage())) {
            $input['image'] = $image;
        }

        $category->update($input);
        if (count($seo)) {
            ApiSeoService::set($category, $seo);
        }
        return $this->showData($category);
    }

    protected function uploadImage(?UploadedFile $file): ?string
    {
        if (!$file) {
            return null;
        }

        if ($file->getMimeType() === 'image/gif') {
            return FileManager::uploadRaw($file, 'product-category');
        }

        $uploader = new FileUploader($file, 'uploads/product-category');
        $uploader->setExtensions(['jpeg', 'webp', 'png', 'jpg']);
        $uploader->setSizes(['big' => [300, 300], 'medium' => [150, 150]]);

        return $uploader->upload();
    }
}
