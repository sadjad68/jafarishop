<?php

namespace App\Modules\Product\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Modules\General\Helper\NumberHelper;
use App\Modules\Product\Entities\Product;
use App\Modules\Product\Entities\ProductVariant;

class ProductsImport implements ToModel, WithHeadingRow
{
    public array $updatedProductIds = [];

    public function model(array $row)
    {
        $product = Product::find(@$row['شناسه محصول']);
        if ($product) {
            if ($product->price_formula == null) {
                if (count($product->variants) == 0) {
                    //Todo : use setters for formatting numbers
                    $product->update([
                        'price' => intval(NumberHelper::persian2LatinDigit($row['قیمت'])),
                        'discounted_price' => intval(NumberHelper::persian2LatinDigit($row['قیمت بعد از تخفیف'])),
                        'final_price' => intval(NumberHelper::persian2LatinDigit($row['قیمت بعد از تخفیف'])) != 0 ?
                            intval(NumberHelper::persian2LatinDigit($row['قیمت بعد از تخفیف'])) :
                            intval(NumberHelper::persian2LatinDigit($row['قیمت'])),
                        'stock' => intval(NumberHelper::persian2LatinDigit($row['موجودی'])),
                    ]);
                } else {
                    $variant = ProductVariant::find($row['شناسه متغییر']);
                    if ($variant) {
                        $variant->update([
                            'price' => intval(NumberHelper::persian2LatinDigit($row['قیمت'])),
                            'discounted_price' => intval(NumberHelper::persian2LatinDigit($row['قیمت بعد از تخفیف'])),
                            'final_price' => intval(NumberHelper::persian2LatinDigit($row['قیمت بعد از تخفیف'])) != 0 ?
                                intval(NumberHelper::persian2LatinDigit($row['قیمت بعد از تخفیف'])) :
                                intval(NumberHelper::persian2LatinDigit($row['قیمت'])),
                            'stock' => intval(NumberHelper::persian2LatinDigit($row['موجودی'])),
                        ]);
                    }
                    //Todo : put in observers OR setters
                    //you have the exact same code at VariantService::create.
                    $sum_stock = $product->variants()->orderBy('final_price', 'ASC')->sum('stock');
                    $minimum_price_variant = $product->variants()
                        ->orderByRaw('CAST(final_price AS UNSIGNED) ASC')
                        ->where('stock', '<>', '0')->where('final_price', '<>', '0')
                        ->first();
                    $product->update([
                        'price' => $minimum_price_variant ? $minimum_price_variant['price'] : 0,
                        'discounted_price' => $minimum_price_variant ? $minimum_price_variant['discounted_price'] : 0,
                        'final_price' => $minimum_price_variant ? $minimum_price_variant['final_price'] : 0,
                        'stock' => $sum_stock,
                    ]);
                }

                if (!in_array($product->id, $this->updatedProductIds)) {
                    $this->updatedProductIds[] = $product->id;
                }
            }
        }
        return null;
    }
}
