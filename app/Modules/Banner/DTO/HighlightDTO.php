<?php

namespace App\Modules\Banner\DTO;

use Illuminate\Http\UploadedFile;
use App\Modules\Banner\Http\Requests\HighlightRequest;

class HighlightDTO
{
    protected string $title;
    protected string $type;
    protected string|null $place;
    protected string $target;
    protected int|null $tag_id;
    protected string|null $link;
    protected UploadedFile|null $image;
    protected UploadedFile|null $image_mobile;
    protected string $width;
    protected string $height;
    protected int|null $show_in_first_page;

    public static function fromRequest(HighlightRequest $request)
    {
        $self = new self();
        $self->title = $request->get('title');
        $self->type = 'desktop';
        $self->target = $request->input('target', 'place');
        if ($self->target === 'tag') {
            $self->place = null;
            $self->tag_id = $request->filled('tag_id') ? (int) $request->input('tag_id') : null;
        } else {
            $self->place = $request->get('place');
            $self->tag_id = null;
        }
        $self->image = $request->file('image');
        $self->width = $request->get('width');
        $self->height = $request->get('height');
        $self->link = $request->get('link');
        $self->show_in_first_page = $request->has('show_in_first_page') ? 1 : 0;
        return $self;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function getTarget(): string
    {
        return $this->target;
    }

    public function getTagId(): ?int
    {
        return $this->tag_id;
    }

    public function setPlace(?string $place): void
    {
        $this->place = $place;
    }

    public function getLink(): string|null
    {
        return $this->link;
    }

    public function setLink(string $link): void
    {
        $this->link = $link;
    }

    public function getImage(): ?UploadedFile
    {
        return $this->image;
    }

    public function getImageMobile(): ?UploadedFile
    {
        return $this->image_mobile;
    }

    public function setImage(?UploadedFile $image): void
    {
        $this->image = $image;
    }

    public function getWidth(): string
    {
        return $this->width;
    }

    public function setWidth(string $width): void
    {
        $this->width = $width;
    }

    public function getHeight(): string
    {
        return $this->height;
    }

    public function setHeight(string $height): void
    {
        $this->height = $height;
    }

    public function getShowInFirstPage(): ?int
    {
        return $this->show_in_first_page;
    }

    public function setShowInFirstPage(?int $show_in_first_page): void
    {
        $this->show_in_first_page = $show_in_first_page;
    }
}
