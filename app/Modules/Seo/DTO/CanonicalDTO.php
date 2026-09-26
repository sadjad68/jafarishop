<?php

namespace App\Modules\Seo\DTO;

use App\Modules\Seo\Http\Requests\CanonicalRequest;

class CanonicalDTO
{
    protected string|null $url ;
    public function getUrl(): string
    {
        return $this->url;
    }
    protected string|null $canonical ;
    public function getCanonical(): string
    {
        return $this->canonical;
    }

    public static function fromRequest(CanonicalRequest $request)
    {
        $self = new self();
        $self->url = '/'.trim(str_replace(url('/'), "", $request->get('url')),'/');
        $self->canonical = '/'.trim(str_replace(url('/'), "", $request->get('canonical')),'/');
        return $self;
    }

}
