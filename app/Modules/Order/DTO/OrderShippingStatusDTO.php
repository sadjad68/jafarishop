<?php

namespace App\Modules\Order\DTO;

use App\Modules\Order\Http\Requests\OrderShippingStatusRequest;


class OrderShippingStatusDTO
{
    protected string $title;
    public function getTitle(): string
    {
        return $this->title;
    }

    protected string $color;
    public function getColor(): string
    {
        return $this->color;
    }

    public bool $default;
    public function getDefault(): bool
    {
        return $this->default;
    }
    public bool $for_send;
    public function getForSend(): bool
    {
        return $this->for_send;
    }
    public bool $sending_sms;
    public function getSendingSms(): bool
    {
        return $this->sending_sms;
    }
    public static function fromRequest(OrderShippingStatusRequest $request)
    {

        $self = new self();
        $self->title = $request->get('title');
        $self->color = $request->get('color');
        $self->image = $request->file('image');
        $self->default = $request->has('default');
        $self->for_send = $request->has('for_send');
        $self->sending_sms = $request->has('sending_sms');
        return $self;
    }
}
