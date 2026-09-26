<?php

namespace App\Modules\Service\DTO;
use App\Modules\Service\Http\Requests\FaqRequest;

class FaqDTO
{

    public function getFaqs(): array|null
    {
        return $this->faqs;
    }
    protected string $service_id;
    public function getServiceId() : int
    {
        return $this->service_id;
    }
    public static function fromRequest(FaqRequest $request)
    {
        $self = new self();
        $self->faqs = $request->get('faqs');
        $self->service_id = $request->get('service_id');
        return $self;
    }
}
