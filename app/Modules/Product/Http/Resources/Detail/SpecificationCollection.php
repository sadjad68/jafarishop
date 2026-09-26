<?php

namespace App\Modules\Product\Http\Resources\Detail;


use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Modules\Product\Entities\Specification;


class SpecificationCollection extends ResourceCollection
{
    public function toArray($request)
    {
        $product = $this->resource;
        $specificationsByMain = [];

        $specificationIds = $product->variants
            ->flatMap(fn($variant) => $variant->specifications->pluck('id'))
            ->values();

        foreach ($product->main_specifications as $main_specification) {
            $childSpecifications = Specification::whereIn('id', $specificationIds)
                ->where('parent_id', $main_specification->id)
                ->get(['id', 'title', 'color_code'])
                ->unique('id')
                ->values();

            if ($childSpecifications->isNotEmpty()) {
                // برای هر Specification، variants مرتبط رو محاسبه می‌کنیم
                $childSpecifications->each(function ($spec) use ($product) {
                    $relatedVariants = $product->variants->filter(
                        fn($variant) => $variant->specifications->pluck('id')->contains($spec->id)
                    );
                    $spec->variants = $relatedVariants->values();
                });

                $main_specification->children = $childSpecifications;
                $specificationsByMain[] = new MainSpecificationResource($main_specification);
            }
        }

        return $specificationsByMain;
    }
}
