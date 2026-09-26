<?php

namespace App\Modules\Seo\DTO;

use App\Modules\Seo\Http\Requests\RedirectRequest;

class RedirectDTO
{
    protected string|null $old_address;

    public function getOldAddress(): string
    {
        return $this->old_address;
    }

    protected string|null $new_address;

    public function getNewAddress(): string
    {
        return $this->new_address;
    }
    protected string|null $type;
    public function getType(): string
    {
        return $this->type;
    }

    public static function fromRequest(RedirectRequest $request)
    {

        $self = new self();
        $self->old_address = trim(str_replace(url('/'), "", $request->get('old_address')), '/');
        $self->new_address = str_replace(url('/'), "", $request->get('new_address'));
        if ($self->new_address != "/") {
            $self->new_address = trim(str_replace(url('/'), "", $request->get('new_address')), '/');
        }
        $self->type = $request->get('type');
        return $self;
    }

}
