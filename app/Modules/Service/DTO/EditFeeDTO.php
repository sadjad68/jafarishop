<?php

namespace App\Modules\Service\DTO;

use App\Modules\General\Helper\NumberHelper;
use App\Modules\Service\Http\Requests\FeeRequest;
class EditFeeDTO
{
    protected string $minimum_price;
    public function getMinimumPrice(): string
    {
        return $this->minimum_price;
    }
    protected string $maximum_price;
    public function getMaximumPrice(): string
    {
        return $this->maximum_price;
    }
    protected string|null $description;
    public function getDescription(): string|null
    {
        return $this->description;
    }

    protected int|null $service_id;
    public function getServiceId(): ?int
    {
        return $this->service_id;
    }

    public static function fromRequest(FeeRequest $request)
    {
        $self = new self();
        $self->minimum_price = intval(NumberHelper::persian2LatinDigit($request['minimum_price']));
        $self->maximum_price = intval(NumberHelper::persian2LatinDigit($request['maximum_price']));
        $self->service_id = @$request->get('service_id');
        $self->description = @$request['description'];
        return $self;
    }
}
