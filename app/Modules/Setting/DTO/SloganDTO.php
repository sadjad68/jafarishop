<?php

namespace App\Modules\Setting\DTO;

use App\Modules\Setting\Http\Requests\SloganRequest;
use Illuminate\Http\UploadedFile;

class SloganDTO
{
    protected string $value;
    public function getValue(): string
    {
        return $this->value;
    }

    protected UploadedFile|null $con;
    public function getIcon(): ?UploadedFile
    {
        return $this->icon;
    }
    public function getActive(): bool
    {
        return $this->active;
    }

    public static function fromRequest(SloganRequest $request)
    {
        $self = new self();
        $self->value = $request->get('value');
        $self->icon = $request->file('icon');
        $self->active = $request->has('active');
        return $self;
    }
}
