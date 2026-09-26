<?php
namespace App\Modules\Product\Services;

use App\Modules\General\Helper\FileManager;
use App\Modules\Product\DTO\BrandDTO;
use App\Modules\Product\Entities\Brand;

class BrandService
{
    public function create(BrandDTO $brandDTO)
    {
        $image = $brandDTO->getImage() ? FileManager::upload($brandDTO->getImage(),"brand") : null;
        $brand = Brand::create([
            'title' => $brandDTO->getTitle(),
            'description' => $brandDTO->getDescription(),
            'url' => $brandDTO->getUrl(),
            'image' => $image,
            'active' => $brandDTO->getActive(),
            'show_in_first_page' => $brandDTO->showFirstPage(),
        ]);

    }
    public function update(int $id, BrandDTO $brandDTO)
    {
        $brand = Brand::findOrfail($id);
        $image = $brandDTO->getImage() ? FileManager::upload($brandDTO->getImage(),"brand") : $brand->getRawOriginal('image');
        $brand->update([
            'title' => $brandDTO->getTitle(),
            'description' => $brandDTO->getDescription(),
            'url' => $brandDTO->getUrl(),
            'image' => $image,
            'active' => $brandDTO->getActive(),
            'show_in_first_page' => $brandDTO->showFirstPage(),

        ]);

    }

    public function deleteOne(int $id): void
    {
        $brand = Brand::findOrFail($id);

        //delete image
        if ($brand->image) {
            FileManager::delete("brand/" . $brand->getRawOriginal('image'));
        }
        $brand->delete();
    }

    //
    public static function findAll($query = [], $except_id = null, $limit = null)
    {
        $brands = Brand::query();
        if ($except_id) {
            $brands->where('id', '<>', $except_id);
        }
        if (isset($query['first_page'])) {
            $brands->firstPage();
        }
        if (isset($query['has_products'])) {
            if(isset($query['brand_categories'])){
                $brands->whereHas('products', function ($query2) use ($query) {
                    $query2->whereHas('categories', function ($query3) use ($query) {
                        $query3->whereIn("product_category_id", $query['brand_categories']);
                    });
                });
            }
            else{
                $brands->whereHas('products');
            }

        }
        if (isset($query['select'])) {

            $brands->select($query['select']);
        }
        if (isset($query['filter_brands'])) {
            $brands->whereIn('id',$query['filter_brands']);
        }
        if (isset($query['title'])) {
            $brands->where('title','LIKE','%'.$query['title'].'%');
        }
        if ($limit != null) {
            return $brands->orderby('id', 'DESC')->take($limit)->get();
        }else{

            return $brands->orderby('id', 'DESC')->get();
        }
    }
    public static function findOne($url)
    {
        return Brand::where('url',$url)->firstOrFail();
    }


}
