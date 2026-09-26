<?php

namespace App\Modules\Page\DTO;

use App\Modules\Page\Http\Requests\PageRequest;
use Illuminate\Http\UploadedFile;

class PageDTO
{
    protected string $title;
    public function getTitle(): string
    {
        return $this->title;
    }

    protected string $url;
    public function getUrl(): string
    {
        return $this->url;
    }
    protected string|null $description;
    public function getDescription(): string|null
    {
        return $this->description;
    }

    public static function fromRequest(PageRequest $request)
    {
        $self = new self();
        $self->title = $request->get('title');
        $self->description = $request->get('description');
        $self->url = trim(str_replace(' ', '-',@$request->get('url')));
        return $self;
    }

}
