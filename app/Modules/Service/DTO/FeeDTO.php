<?php

namespace App\Modules\Service\DTO;

use App\Modules\General\Helper\NumberHelper;
use App\Modules\Service\Http\Requests\FeeRequest;

class FeeDTO
{
    protected  $minimum_price;
    public function getMinimumPrice()
    {
        return $this->minimum_price;
    }
    protected  $maximum_price;
    public function getMaximumPrice()
    {
        return $this->maximum_price;
    }
    protected  $description;
    public function getDescription()
    {
        return $this->description;
    }
    protected int|null $service_id;
    public function getServiceId(): int|null
    {
        return $this->service_id;
    }
    protected bool|null $has_error;
    public function getHasError(): bool|null
    {
        return $this->has_error;
    }
    private array $dto = [];

    public function getDto(): array
    {
        return $this->dto;
    }


    public static function fromRequest(FeeRequest $request): self
    {
        $self = new self();
        $self->has_error = false;

        foreach ($request['minimum_price'] as $key => $item) {
            if ($item != null && $request['maximum_price'][$key] !== null) {
                if ($item > $request['maximum_price'][$key]) {
                    $self->has_error = true;
                    continue;
                }

                $self->dto[] = [

                    'minimum_price' => intval(NumberHelper::persian2LatinDigit($item)),
                    'maximum_price' => intval(NumberHelper::persian2LatinDigit($request['maximum_price'][$key])),
                    'description' => $request['description'][$key],
                    'service_id' => $request['service_id'][$key],
                ];
            }
        }

        return $self;
    }
}
