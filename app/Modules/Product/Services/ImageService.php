<?php

namespace App\Modules\Product\Services;

use App\Modules\General\Helper\FileManager;
use App\Modules\General\Helper\FileUploader;
use App\Modules\Product\DTO\ImageMainVariantDTO;
use App\Modules\Product\Entities\Product;
use App\Modules\Service\Entities\Service;
use App\Modules\Service\DTO\WorkSampleDTO;
use App\Modules\Service\Entities\WorkSample;
use App\Modules\Service\Entities\WorkSampleImage;
use App\Modules\Product\DTO\ImageDTO;
use App\Modules\Product\Entities\Image;

class ImageService
{
    public function create(ImageDTO $imageDTO): void
    {
        foreach ($imageDTO->getImage() as $item) {
            $image = null;
            if ($item) {
                $uploader = new FileUploader($item, "uploads/product");
                $uploader->setExtensions(["jpeg", "webp", "png", "jpg"]);
                $uploader->setSizes(
                    [
                        "big" => [1000, 1000],
                        "medium" => [500, 500],
                        "small" => [75, 75],
                    ]
                );
                $image = $uploader->upload();
            }
            Image::create([
                'image' => $image,
                'product_id' => $imageDTO->getProductId(),
            ]);
        }
        $product = Product::findOrFail($imageDTO->getProductId());
        $first_image = $product->images->first();

        if ($first_image) {
            if ($first_image->thumbnail != 1) {
                $product->update([
                    'image' => $first_image->image,
                ]);
            } else {
                $product->update([
                    'image' => $product->images()->first()->image,
                ]);
            }
        }
        if ($imageDTO->getVarinats()) {
            foreach ($imageDTO->getVarinats() as $key => $variant) {
                $image = Image::findOrFail($key);
                $image->variants()->sync($variant);
            }
            $empty_images = $product->images()->whereNotIn('id', array_keys($imageDTO->getVarinats()))->whereHas('variants')->get();
            foreach ($empty_images as $empty){
                $empty->variants()->sync([]);
            }
        }else{
            $empty_images = $product->images()->whereHas('variants')->get();
            foreach ($empty_images as $empty){
                $empty->variants()->sync([]);
            }
        }

    }
    public function getProductImages(int $productId)
    {
        $images = Image::with(['variants' => function($q) {
            $q->select('product_variants.id');
        }])->where('product_id', $productId)->select('id', 'image', 'product_id')->get();
        $images->each(function($image) {
            $image->variants->makeHidden('pivot');
        });
        return $images;
    }

    public function findOne($id)
    {
        return Image::find($id);
    }
}
