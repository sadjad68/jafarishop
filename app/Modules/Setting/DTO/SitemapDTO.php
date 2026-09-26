<?php

namespace App\Modules\Setting\DTO;

use App\Modules\Setting\Http\Requests\SitemapRequest;
use App\Modules\Setting\Http\Requests\SloganRequest;
use Illuminate\Http\UploadedFile;

class SitemapDTO
{
    protected string $p_name;
    public function getPName(): string
    {
        return $this->p_name;
    }


    protected string $key;
    public function getKey(): string
    {
        return $this->key;
    }

    protected string $change_frequency;
    public function getChangeFrequency(): string
    {
        return $this->change_frequency;
    }


    protected string $priority;
    public function getPriority(): string
    {
        return $this->priority;
    }


    public static function fromRequest(SitemapRequest $request)
    {
        $self = new self();
        $self->key = $request->get('key');
        $self->p_name = $request->get('p_name');
        $self->change_frequency = $request->get('change_frequency');
        $self->priority = $request->get('priority');
        return $self;
    }
}
