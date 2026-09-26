<?php

namespace App\Modules\Product\Services;

use App\Modules\General\Helper\FileManager;
use App\Modules\General\Helper\NumberHelper;
use App\Modules\Product\DTO\MainVariantDTO;
use App\Modules\Product\DTO\VariantDTO;
use App\Modules\Product\Entities\ProductVariant;
use App\Modules\Product\Entities\Specification;
use App\Modules\Tag\Entities\Taggable;
use App\Modules\Product\DTO\SpfDTO;
use App\Modules\Product\DTO\VideoFaqDTO;
use App\Modules\Product\Entities\Brand;
use App\Modules\Product\Entities\Product;
use App\Modules\Product\Entities\Property;
use App\Modules\Product\Entities\Video;
use App\Modules\Product\Entities\ProductSpecification;
use App\Modules\Product\Jobs\SendProductNotificationJob;


class VariantService
{
    public function create(VariantDTO $DTO)
    {
        $product = Product::findOrFail($DTO->getDto()['product_id']);
        $product->main_specifications()->sync($DTO->getMainVariantSpecificationId());
        foreach ($DTO->getDto()['variants'] as $variant) {
            if ($product->price_formula != null) {
                try {
                    $priceFormula = $product->price_formula;
                    $class = 'App\\Modules\\Product\\Library\\' . $priceFormula;
                    $response = new $class(intval(NumberHelper::persian2LatinDigit($variant['weight'])));
                    $price = $response->fetchData();
                    $discounted_price = 0;
                    $final_price = $price;
                } catch (\Exception $e) {
                    \Log::info("خطا در دریافت اطلاعات: " . $e->getMessage());
                }
            } else {
                $price = intval(NumberHelper::persian2LatinDigit($variant['price']));
                $discounted_price = intval(NumberHelper::persian2LatinDigit($variant['discounted_price']));
                $final_price = intval(NumberHelper::persian2LatinDigit($variant['discounted_price'])) != 0 ?
                    intval(NumberHelper::persian2LatinDigit($variant['discounted_price'])) :
                    intval(NumberHelper::persian2LatinDigit($variant['price']));
            }
            if ($variant['variant_id'] == null) {
                $newVariant = ProductVariant::create([
                    'product_id' => $DTO->getDto()['product_id'],
                    'specification_parent_id' => $DTO->getDto()['specification_parent_id'],
//                    'specification_id'=>$variant['specification_id'],
                    'price_affective' => intval(@$variant['price_affective']),
                    'stock' => intval(NumberHelper::persian2LatinDigit($variant['stock'])),
                    'weight' => intval(NumberHelper::persian2LatinDigit($variant['weight'])),
                    'price' => $price,
                    'discounted_price' => $discounted_price,
                    'final_price' => $final_price,
                ]);
                $newVariant->specifications()->sync($variant['specification_id']);
            } else {

                $check = ProductVariant::findOrFail($variant['variant_id']);
                $check->update([
//                    'specification_id'=>$variant['specification_id'],
                    'price_affective' => intval(@$variant['price_affective']),
                    'stock' => intval(NumberHelper::persian2LatinDigit($variant['stock'])),
                    'weight' => intval(NumberHelper::persian2LatinDigit($variant['weight'])),
                    'price' => $price,
                    'discounted_price' => $discounted_price,
                    'final_price' => $final_price,
                ]);
                $check->specifications()->sync($variant['specification_id']);

            }
        }

        $sum_stock = $product->variants()->orderBy('final_price', 'ASC')->sum('stock');
        $minimum_price_variant = $product->variants()
            ->orderByRaw('CAST(final_price AS UNSIGNED) ASC')
            ->where('stock', '<>', '0')->where('final_price', '<>', '0')
            ->first();
        $product->update(
            [
                'price' => $minimum_price_variant ? $minimum_price_variant['price'] : 0,
                'discounted_price' => $minimum_price_variant ? $minimum_price_variant['discounted_price'] : 0,
                'final_price' => $minimum_price_variant ? $minimum_price_variant['final_price'] : 0,
                'stock' => $sum_stock,
            ]
        );
        SendProductNotificationJob::dispatch([$product->id]);
    }

    public function delete(int $id): void
    {
        $variant = ProductVariant::findOrFail($id);

        $variant->delete();
    }

    public static function getProductImagesSizeSeperated($images)
    {

        $images_format = [];
        foreach ($images as $image) {
            $images_format[] = [
                'id' => $image->id,
                'image_big' => $image->getImage('big'),
                'image_small' => $image->getImage('small'),
                'image_medium' => $image->getImage('medium'),
            ];
        }
        return $images_format;
    }

}
