<?php

namespace App\Modules\Product\DTO;
use App\Modules\Product\Http\Requests\SpfRequest;
use App\Modules\Product\Http\Requests\VideoFaqRequest;

class SpfDTO
{

    public function getProprties(): array|null
    {
        return $this->properties;
    }
    public function getTags(): array|null
    {
        return $this->tags;
    }
    public function getSpecificationProductValues(): array|null
    {
        return $this->specification_product_values;
    }
    public function getSpecifications(): array|null
    {
        return $this->specifications;
    }
    protected string $product_id;
    public function getProductId() : int
    {
        return $this->product_id;
    }
    public static function fromRequest(SpfRequest $request)
    {
        $self = new self();
        $self->properties = $request->get('properties');
        $self->tags = $request->get('tags');
        $self->specification_product_values = $request->get('specification_product_values');
        $self->specifications = $request->get('specifications');
        $self->product_id = $request->get('product_id');
        return $self;
    }
}
