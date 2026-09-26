<?php

namespace App\Modules\Setting\DTO;

use App\Modules\Setting\Http\Requests\BranchRequest;

class BranchDTO
{
    protected string $title;
    public function getTitle(): string
    {
        return $this->title;
    }


    protected string $address;
    public function getAddress(): string
    {
        return $this->address;
    }

    protected int|null $main;
    public function getMain(): int|null
    {

        return $this->main;
    }
    protected string|null $map;
    public function getMap(): string|null
    {
        return $this->map;
    }
    public static function fromRequest(BranchRequest $request)
    {
        $self = new self();
        $self->title = $request->get('title');
        $self->address = $request->get('address');
        $self->map = $request->get('map');
        $self->main = @$request->has('main') ? 1 : 0;
        return $self;
    }
}
