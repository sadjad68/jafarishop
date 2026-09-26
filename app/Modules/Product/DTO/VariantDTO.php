<?php

namespace App\Modules\Product\DTO;

use App\Modules\General\Helper\NumberHelper;
use App\Modules\Product\Http\Requests\VariantRequest;

class VariantDTO
{
    protected bool $has_error = false;
    protected bool $has_error_specification = false;
    protected array $dto = [];

    public function getHasError(): bool
    {
        return $this->has_error;
    }

    public function getHasErrorSpecification(): bool
    {
        return $this->has_error_specification;
    }

    public function getDto(): array
    {
        return $this->dto;
    }
    protected array $main_variant_specification_id;
    public function getMainVariantSpecificationId() : array
    {
        return $this->main_variant_specification_id;
    }

    public static function fromRequest(VariantRequest $request)
    {

        $self = new self();
        $self->main_variant_specification_id = $request->get('main_variant_specification_id');
        $self->dto = [
            'product_id' => $request->get('product_id'),
            'specification_parent_id' => $request->get('specification_parent_id'),
            'variants' => []
        ];

        foreach ($request['variants'] as $key => $item) {
            $discountedPrice = $item['discounted_price'] ? intval(NumberHelper::persian2LatinDigit($item['discounted_price'])) : 0;
            $price = $item['price'] ? intval(NumberHelper::persian2LatinDigit($item['price'])) : 0;
            // بررسی مشخصات (که اکنون یک آرایه است)
            if (!isset($item['specifications']) || empty($item['specifications'])) {
                $self->has_error_specification = true;
                continue;
            }

            if ($discountedPrice != 0 && $discountedPrice >= $price) {
                $self->has_error = true;
                continue;
            }

            $self->dto['variants'][] = [
                'variant_id' => $item['variant_id'],
                'specification_id' => $item['specifications'], // اکنون یک آرایه است
                'price_affective' => 1,
                'stock' => intval(NumberHelper::persian2LatinDigit($item['stock'])),
                'weight' => intval(NumberHelper::persian2LatinDigit($item['weight'])),
                'price' => $price,
                'discounted_price' => $discountedPrice,
            ];
        }

        return $self;
    }
}
