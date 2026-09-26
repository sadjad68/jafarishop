<?php

namespace App\Modules\Gallery\DTO;

use App\Modules\Gallery\Http\Requests\GalleryCategoryRequest;
use Illuminate\Http\UploadedFile;

class GalleryCategoryDTO
{
    protected string $title;
    public function getTitle(): string
    {
        return $this->title;
    }

    protected UploadedFile|null $image;
    public function getImage(): UploadedFile|null
    {
        return $this->image;
    }
    protected string $url;
    public function getUrl(): string
    {
        return $this->url;
    }

    protected int|null $show_in_first_page;
    public function getShowInFirstPage(): int|null
    {

        return $this->show_in_first_page;
    }

    public static function fromRequest(GalleryCategoryRequest $request)
    {
        $self = new self();
        $self->title = $request->get('title');
        $self->image = $request->file('image');
        $self->url = trim(str_replace(' ', '-',@$request->get('url')));
        $self->show_in_first_page = @$request->has('show_in_first_page') ? 1 : 0;
        return $self;
    }
}
