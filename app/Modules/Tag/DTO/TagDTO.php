<?php

namespace App\Modules\Tag\DTO;

use App\Modules\Tag\Http\Requests\TagRequest;
use App\Modules\Page\Http\Requests\PageRequest;
use Illuminate\Http\UploadedFile;

class TagDTO
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
    public function getFirstPageImage(): UploadedFile|null
    {
        return $this->first_page_image;
    }
    public function getFirstPageIcon(): UploadedFile|null
    {
        return $this->first_page_icon;
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
    protected int|null $show_status;
    public function getActive(): int|null
    {

        return $this->active;
    }
    public function getShowFirstPage(): int|null
    {

        return $this->show_in_first_page;
    }
    public static function fromRequest(TagRequest $request)
    {
        $self = new self();
        $self->title = $request->get('title');
        $self->description = $request->get('description');
        $self->image = $request->file('image');
        $self->first_page_image = $request->file('first_page_image');
        $self->first_page_icon = $request->file('first_page_icon');
        $self->url = trim(str_replace(' ', '-',@$request->get('url')));
        $self->active = @$request->has('active') ? 1 : 0;
        $self->show_in_first_page = @$request->has('show_in_first_page') ? 1 : 0;
        return $self;
    }

}
