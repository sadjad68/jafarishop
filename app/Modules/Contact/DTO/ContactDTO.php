<?php

namespace App\Modules\Contact\DTO;

use App\Modules\General\Helper\NumberHelper;
use App\Modules\Contact\Http\Requests\ContactRequest;

class ContactDTO
{

    protected string $title;
    public function getTitle(): string
    {
        return $this->title;
    }

    protected string $name;
    public function getName(): string
    {
        return $this->name;
    }
    protected string $mobile;
    public function getMobile(): string
    {
        return $this->mobile;
    }
    protected string $message;
    public function getMessage(): string
    {
        return $this->message;
    }
    public static function fromRequest(ContactRequest $request)
    {
        $self = new self();
        $self->title = $request->get('title');
        $self->name = $request->get('name');
        $self->mobile = NumberHelper::persian2LatinDigit($request->get('mobile'));
        $self->message = $request->get('message');

        return $self;
    }
}
